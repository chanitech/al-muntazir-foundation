<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VotingNotificationService
{
    public static function voteSuccess($member)
    {
        /*
        |--------------------------------------------------------------------------
        | Future SMS Integration Point
        |--------------------------------------------------------------------------
        */

        Log::info("Vote success notification for member: ".$member->id);
    }
}