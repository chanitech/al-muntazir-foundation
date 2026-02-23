<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Election;
use Symfony\Component\HttpFoundation\Response;

class VoteIntegrityMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $electionId = $request->input('election_id');

        if (!$electionId) {
            return response()->json([
                'message' => 'Election ID missing'
            ], 400);
        }

        $election = Election::find($electionId);

        if (!$election) {
            return response()->json([
                'message' => 'Election not found'
            ], 404);
        }

        // Election time validation
        if (now()->lt($election->start_time) ||
            now()->gt($election->end_time)) {

            return response()->json([
                'message' => 'Election is not active'
            ], 403);
        }

        return $next($request);
    }
}