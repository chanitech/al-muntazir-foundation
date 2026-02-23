<?php

namespace App\Services;

use App\Models\Election;
use App\Models\Vote;
use App\Models\Candidate;
use Exception;

class VotingService
{
    public function vote(
        $memberId,
        $candidateId,
        $electionId,
        $positionId,
        $ip = null
    )
    {

        $election = Election::findOrFail($electionId);

        /*
        |--------------------------------------------------------------------------
        | Election Time Validation
        |--------------------------------------------------------------------------
        */
$now = now();

if(!$election->is_active){
    throw new \Exception('Election is not active');
}

if($election->start_time && $now->lt($election->start_time)){
    throw new \Exception('Voting has not started yet');
}

if($election->end_time && $now->gt($election->end_time)){
    throw new \Exception('Voting period has ended');
}

        /*
        |--------------------------------------------------------------------------
        | Duplicate Voting Protection
        |--------------------------------------------------------------------------
        */

        $exists = Vote::where([
            'member_id'=>$memberId,
            'election_id'=>$electionId,
            'position_id'=>$positionId
        ])->exists();

        if($exists){
            throw new Exception('You already voted');
        }

        /*
        |--------------------------------------------------------------------------
        | Candidate Validation
        |--------------------------------------------------------------------------
        */

        $candidate = Candidate::where('id',$candidateId)
            ->where('election_id',$electionId)
            ->where('position_id',$positionId)
            ->where('is_approved',true)
            ->first();

        if(!$candidate){
            throw new Exception('Candidate not allowed');
        }

        /*
        |--------------------------------------------------------------------------
        | Store Vote
        |--------------------------------------------------------------------------
        */

        return Vote::create([
            'member_id'=>$memberId,
            'candidate_id'=>$candidateId,
            'election_id'=>$electionId,
            'position_id'=>$positionId,
            'ip_address'=>$ip
        ]);
    }
}