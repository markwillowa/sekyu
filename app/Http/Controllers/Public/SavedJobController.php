<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;

class SavedJobController extends Controller
{
    public function toggle(Request $request, JobPost $jobPost)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user->savedJobs()->toggle($jobPost->id);

        $isSaved = $user->savedJobs()->where('job_post_id', $jobPost->id)->exists();

        return response()->json([
            'is_saved' => $isSaved,
            'message' => $isSaved ? 'Job saved successfully' : 'Job removed from saved jobs'
        ]);
    }
}
