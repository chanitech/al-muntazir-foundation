<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\VotingService;
use App\Models\Member;
use App\Models\Position;
use App\Exceptions\VotingException;
use Illuminate\Support\Facades\DB;



class VotingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Election List
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $elections = Election::latest()->get();

        return view('admin.voting.index', compact('elections'));
    }

    /*
    |--------------------------------------------------------------------------
    | Show Election Voting Page
    |--------------------------------------------------------------------------
    */
    public function showElection(Request $request, $id)
    {
        $election = Election::findOrFail($id);

        $candidates = Candidate::where('election_id', $id)
            ->where('is_approved', true)
            ->with('member')
            ->get()
            ->groupBy('position_id');

        $positions = Position::whereIn('id', $candidates->keys())->get()->keyBy('id');

        // Positions the logged-in member has already voted for.
        $memberId = $request->user()?->member?->id;
        $votedPositions = $memberId
            ? Vote::where('election_id', $id)->where('member_id', $memberId)->pluck('position_id')->all()
            : [];

        return view('admin.voting.vote', compact(
            'election',
            'candidates',
            'positions',
            'votedPositions'
        ));
    }

    public function analytics(Request $request)
{
    $electionId = $request->get('election_id');

    $elections = Election::latest()->get();

    $election = null;
    $totalVotes = 0;
    $turnoutRate = 0;
    $candidateStats = collect();

    if($electionId){

        $election = Election::findOrFail($electionId);

        $totalVotes = Vote::where('election_id',$electionId)->count();

        $activeMembers = Member::where('status','Active')->count();

        $turnoutRate = $activeMembers > 0
            ? round(($totalVotes/$activeMembers)*100,2)
            : 0;

        $candidateStats = Candidate::with('member')
            ->where('election_id',$electionId)
            ->where('is_approved',true)
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->get();
    }

    return view('admin.voting.analytics',compact(
        'elections',
        'election',
        'totalVotes',
        'turnoutRate',
        'candidateStats'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | Store Vote
    |--------------------------------------------------------------------------
    */
    public function storeVote(Request $request, VotingService $service)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
            // One candidate per position: votes[position_id] = candidate_id
            'votes' => 'required_without:candidate_id|array',
            'votes.*' => 'nullable|integer',
            // Older single-choice form
            'candidate_id' => 'required_without:votes|integer',
        ], [
            'votes.required_without' => 'Please choose a candidate.',
        ]);

        $member = $request->user()?->member;

        if (! $member) {
            return back()->withErrors('You are not registered as a member.');
        }

        if ($member->status !== 'Active') {
            return back()->withErrors('Your membership is not active.');
        }

        $candidateIds = array_filter($request->filled('votes')
            ? array_values($request->input('votes'))
            : [$request->input('candidate_id')]);

        if ($candidateIds === []) {
            return back()->withErrors('Please choose a candidate.');
        }

        $candidates = Candidate::whereIn('id', $candidateIds)
            ->where('election_id', $request->election_id)
            ->where('is_approved', true)
            ->get();

        if ($candidates->count() !== count($candidateIds)
            || $candidates->pluck('position_id')->duplicates()->isNotEmpty()) {
            return back()->withErrors('Your ballot is not valid. Please choose one candidate per position.');
        }

        try {
            // All votes on the ballot are saved together, or none of them.
            DB::transaction(function () use ($candidates, $service, $member, $request) {
                foreach ($candidates as $candidate) {
                    $service->vote($member->id, $candidate->id, $request->election_id, $candidate->position_id, $request->ip());
                }
            });
        } catch (VotingException $e) {
            return back()->withErrors($e->getMessage());
        } catch (\Throwable $e) {
            \Log::error('Voting Error: '.$e->getMessage());

            return back()->withErrors('Voting failed. Please try again.');
        }

        Cache::forget("election_result_{$request->election_id}");
        Cache::forget("election_total_votes_{$request->election_id}");

        return redirect()
            ->route('voting.election', $request->election_id)
            ->with('success', $candidates->count() === 1 ? 'Your vote has been recorded. Thank you!' : 'Your votes have been recorded. Thank you!');
    }

    /*
    |--------------------------------------------------------------------------
    | Result Dashboard
    |--------------------------------------------------------------------------
    */
    public function results($electionId)
    {
        $election = Election::with('positions')
            ->findOrFail($electionId);

        $cacheKey = "election_result_{$electionId}";

        $results = Cache::remember($cacheKey, 60, function () use ($electionId) {

            return Candidate::with('member')
                ->withCount('votes')
                ->where('election_id', $electionId)
                ->where('is_approved', true)
                ->orderBy('position_id')
                ->orderByDesc('votes_count')
                ->get()
                ->groupBy('position_id');
        });

        $totalVotes = Cache::remember(
            "election_total_votes_$electionId",
            60,
            function () use ($electionId) {
                return Vote::where('election_id', $electionId)->count();
            }
        );

        return view('admin.voting.results', compact(
            'election',
            'results',
            'totalVotes'
        ));
    }

    
}

