<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InterviewCalendarController extends Controller
{
    public function download(Interview $interview)
    {
        $user = auth()->user();

        // Check if user is the applicant or the interviewer or part of the agency
        $isApplicant = $interview->jobApplication->guard_id === $user->id;
        $isInterviewer = $interview->interviewer_id === $user->id;
        $isAgencyStaff = $user->agency_id && $interview->jobApplication->job->agency_id === $user->agency_id;

        if (!$isApplicant && !$isInterviewer && !$isAgencyStaff) {
            abort(403);
        }

        $summary = $interview->title;
        $description = $interview->notes ?? "Interview for {$interview->jobApplication->job->title}";
        $location = $interview->meeting_url ?? $interview->location ?? 'To be determined';

        $start = $interview->scheduled_at;
        $end = $start->copy()->addMinutes($interview->duration_minutes);

        $ics = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PROID:-//SEKYU//Recruit Pro//EN',
            'BEGIN:VEVENT',
            'UID:' . Str::uuid(),
            'DTSTAMP:' . now()->format('Ymd\THis\Z'),
            'DTSTART:' . $start->format('Ymd\THis\Z'),
            'DTEND:' . $end->format('Ymd\THis\Z'),
            'SUMMARY:' . $this->escapeString($summary),
            'DESCRIPTION:' . $this->escapeString($description),
            'LOCATION:' . $this->escapeString($location),
            'STATUS:CONFIRMED',
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        $content = implode("\r\n", $ics);

        return response($content)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="interview-' . $interview->id . '.ics"');
    }

    private function escapeString($string)
    {
        return str_replace([',', ';', "\n"], ['\,', '\;', '\\n'], $string);
    }
}
