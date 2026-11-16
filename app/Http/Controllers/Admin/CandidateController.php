<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Member;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Candidates
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $candidates = Candidate::with([
            'member',
            'election',
            'position'
        ])
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('admin.candidates.index', compact('candidates'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Form
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.candidates.create', [
            'members' => Member::where('status','Active')->get(),
            'elections' => Election::where('is_active',true)->get(),
            'positions' => Position::where('is_active',true)->get()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Candidate Nomination
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'election_id' => 'required|exists:elections,id',
            'position_id' => 'required|exists:positions,id',
            'manifesto' => 'nullable|string'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Nomination
        |--------------------------------------------------------------------------
        */
        $exists = Candidate::where([
            'member_id' => $request->member_id,
            'election_id' => $request->election_id,
            'position_id' => $request->position_id
        ])->exists();

        if ($exists) {
            return back()->withErrors([
                'error' => 'Member already nominated for this position in this election'
            ]);
        }

        Candidate::create([
            'member_id' => $request->member_id,
            'election_id' => $request->election_id,
            'position_id' => $request->position_id,
            'manifesto' => $request->manifesto,
            'is_approved' => true
        ]);

        return redirect()
            ->route('candidates.index')
            ->with('success', 'Candidate nominated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Remove a nomination (only while it has no votes)
    |--------------------------------------------------------------------------
    */
    public function destroy(Candidate $candidate)
    {
        if ($candidate->votes()->exists()) {
            return back()->withErrors(['error' => 'This candidate already has votes and cannot be removed.']);
        }

        $candidate->delete();

        return back()->with('success', 'Nomination removed.');
    }

    /*
    |--------------------------------------------------------------------------
    | Candidate Approval (Admin Only)
    |--------------------------------------------------------------------------
    */
    public function approve($id)
    {
        $candidate = Candidate::findOrFail($id);

        $candidate->update([
            'is_approved' => true
        ]);

        return back()->with('success','Candidate approved successfully');
    }
}