<?php

namespace Tests\Feature\Membership;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\Vote;
use Tests\Concerns\SeedsMembership;
use Tests\TestCase;

class ElectionsAndVotingTest extends TestCase
{
    use SeedsMembership;

    /** An open election with two positions and two candidates each. */
    private function openElection(): array
    {
        $election = Election::create(['name' => 'Leadership 2027', 'start_time' => now()->subHour(), 'end_time' => now()->addDay(), 'is_active' => true]);
        $chair = Position::create(['name' => 'Chairperson']);
        $secretary = Position::create(['name' => 'Secretary']);

        $candidates = [];
        foreach ([$chair, $secretary] as $position) {
            foreach ([1, 2] as $i) {
                $candidates[$position->name][$i] = Candidate::create([
                    'member_id' => $this->member(['full_name' => "{$position->name} Candidate {$i}"])->id,
                    'election_id' => $election->id,
                    'position_id' => $position->id,
                    'is_approved' => true,
                ]);
            }
        }

        return [$election, $chair, $secretary, $candidates];
    }

    public function test_a_member_can_vote_for_every_position_on_one_ballot(): void
    {
        [$election, $chair, $secretary, $candidates] = $this->openElection();
        [$user, $voter] = $this->votingMember();

        $this->actingAs($user)
            ->get("/admin/voting/election/{$election->id}")
            ->assertOk()
            ->assertSee('name="votes['.$chair->id.']"', false)
            ->assertSee('name="votes['.$secretary->id.']"', false);

        $this->post('/admin/voting/vote', [
            'election_id' => $election->id,
            'votes' => [
                $chair->id => $candidates['Chairperson'][1]->id,
                $secretary->id => $candidates['Secretary'][2]->id,
            ],
        ])->assertRedirect(route('voting.election', $election->id))->assertSessionHas('success');

        $this->assertSame(2, Vote::where('member_id', $voter->id)->count());

        $this->get("/admin/voting/election/{$election->id}")->assertSee('You have voted');
    }

    public function test_a_member_cannot_vote_twice_for_the_same_position_and_sees_why(): void
    {
        [$election, $chair, , $candidates] = $this->openElection();
        [$user] = $this->votingMember();
        $this->actingAs($user);

        $this->post('/admin/voting/vote', ['election_id' => $election->id, 'votes' => [$chair->id => $candidates['Chairperson'][1]->id]]);
        $this->post('/admin/voting/vote', ['election_id' => $election->id, 'votes' => [$chair->id => $candidates['Chairperson'][2]->id]])
            ->assertSessionHasErrors();

        $this->assertSame('You have already voted for this position.', session('errors')->first());
        $this->assertSame(1, Vote::count());
    }

    public function test_voting_outside_the_voting_period_is_refused_with_a_clear_reason(): void
    {
        [$election, $chair, , $candidates] = $this->openElection();
        $election->update(['end_time' => now()->subMinute()]);
        [$user] = $this->votingMember();

        $this->actingAs($user)->get("/admin/voting/election/{$election->id}")->assertSee('Voting has ended');

        $this->post('/admin/voting/vote', ['election_id' => $election->id, 'votes' => [$chair->id => $candidates['Chairperson'][1]->id]])
            ->assertSessionHasErrors();
        $this->assertSame('The voting period has ended.', session('errors')->first());
        $this->assertSame(0, Vote::count());
    }

    public function test_a_ballot_with_two_candidates_for_one_position_is_rejected(): void
    {
        [$election, $chair, , $candidates] = $this->openElection();
        [$user] = $this->votingMember();

        $this->actingAs($user)->post('/admin/voting/vote', [
            'election_id' => $election->id,
            'votes' => [$chair->id => $candidates['Chairperson'][1]->id, 999 => $candidates['Chairperson'][2]->id],
        ])->assertSessionHasErrors();

        $this->assertSame(0, Vote::count());
    }

    public function test_results_and_analytics_show_the_votes(): void
    {
        [$election, $chair, , $candidates] = $this->openElection();
        [$user] = $this->votingMember();
        $this->actingAs($user)->post('/admin/voting/vote', ['election_id' => $election->id, 'votes' => [$chair->id => $candidates['Chairperson'][1]->id]]);

        $this->actingAs($this->userWithRole('Super Admin'));
        $this->get("/admin/voting/results/{$election->id}")->assertOk()->assertSee('Chairperson Candidate 1');
        $this->get("/admin/voting/analytics?election_id={$election->id}")->assertOk()->assertSee('Chairperson Candidate 1');
    }

    public function test_an_election_can_be_edited_and_closed(): void
    {
        $election = Election::create(['name' => 'Typo Electoin', 'start_time' => now(), 'end_time' => now()->addDay(), 'is_active' => true]);
        $this->actingAs($this->userWithRole('Admin'));

        $this->put("/admin/elections/{$election->id}", [
            'name' => 'Leadership Election',
            'start_time' => now()->addDay()->format('Y-m-d\TH:i'),
            'end_time' => now()->format('Y-m-d\TH:i'),
        ])->assertSessionHasErrors('end_time');

        $this->put("/admin/elections/{$election->id}", [
            'name' => 'Leadership Election',
            'start_time' => now()->format('Y-m-d\TH:i'),
            'end_time' => now()->addDays(2)->format('Y-m-d\TH:i'),
        ])->assertRedirect(route('elections.index'));

        $election->refresh();
        $this->assertSame('Leadership Election', $election->name);
        $this->assertFalse($election->is_active);
        $this->assertFalse($election->isOpen());
    }

    public function test_positions_can_be_edited_and_duplicate_names_are_refused(): void
    {
        $position = Position::create(['name' => 'Treasurer']);
        Position::create(['name' => 'Secretary']);
        $this->actingAs($this->userWithRole('Admin'));

        $this->put("/admin/positions/{$position->id}", ['name' => 'Secretary'])->assertSessionHasErrors('name');
        $this->put("/admin/positions/{$position->id}", ['name' => 'Finance Secretary'])->assertRedirect(route('positions.index'));

        $this->assertSame('Finance Secretary', $position->fresh()->name);
    }

    public function test_a_nomination_without_votes_can_be_removed(): void
    {
        [$election, $chair, , $candidates] = $this->openElection();
        $this->actingAs($this->userWithRole('Super Admin'));

        $this->delete("/admin/candidates/{$candidates['Chairperson'][2]->id}")->assertSessionHas('success');
        $this->assertSoftDeleted($candidates['Chairperson'][2]);

        Vote::create(['member_id' => $this->member()->id, 'candidate_id' => $candidates['Chairperson'][1]->id, 'election_id' => $election->id, 'position_id' => $chair->id]);
        $this->delete("/admin/candidates/{$candidates['Chairperson'][1]->id}")->assertSessionHasErrors();
        $this->assertNotSoftDeleted($candidates['Chairperson'][1]);
    }

    public function test_members_cannot_manage_elections(): void
    {
        $this->actingAs($this->userWithRole('Member'));

        $this->get('/admin/elections/create')->assertForbidden();
        $this->post('/admin/positions', ['name' => 'Self-made Position'])->assertForbidden();
        $this->get('/admin/voting/analytics')->assertForbidden();
    }
}
