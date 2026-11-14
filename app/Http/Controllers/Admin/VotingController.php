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
    public function showElection($id)
    {
        $election = Election::with('positions')->findOrFail($id);

        $candidates = Candidate::where('election_id', $id)
            ->where('is_approved', true)
            ->with('member')
            ->get()
            ->groupBy('position_id');

        return view('admin.voting.vote', compact(
            'election',
            'candidates'
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
        'candidate_id' => 'required|exists:candidates,id',
        'election_id' => 'required|exists:elections,id',
    ]);

    $user = $request->user();

    if (!$user || !$user->member) {
        return back()->withErrors('You are not registered as a member');
    }

    $member = $user->member;

    if ($member->status !== 'Active') {
        return back()->withErrors('Your membership is not active');
    }

    try {

        /*
        |--------------------------------------------------------------------------
        | Load Candidate + Position Safety Check
        |--------------------------------------------------------------------------
        */

        $candidate = Candidate::where('id', $request->candidate_id)
            ->where('election_id', $request->election_id)
            ->where('is_approved', true)
            ->firstOrFail();

        $positionId = $candidate->position_id;

        /*
        |--------------------------------------------------------------------------
        | Voting Service Execution
        |--------------------------------------------------------------------------
        */

        $service->vote(
            $member->id,
            $candidate->id,
            $request->election_id,
            $positionId,
            $request->ip()
        );

        return redirect()
            ->back()
            ->with('success','Vote submitted successfully');

    } catch (\Exception $e){

        \Log::error('Voting Error: '.$e->getMessage());

        return back()->withErrors('Voting failed. Please try again.');
    }
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

