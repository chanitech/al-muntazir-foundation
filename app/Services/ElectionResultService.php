<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ElectionResultService
{
    public static function getResults($electionId)
    {
        return Cache::remember(
            "election_result_$electionId",
            60,
            function () use ($electionId) {

                return DB::table('votes')
                    ->select(
                        'candidate_id',
                        DB::raw('COUNT(*) as total_votes')
                    )
                    ->where('election_id', $electionId)
                    ->groupBy('candidate_id')
                    ->get();

            }
        );
    }
}