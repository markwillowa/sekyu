<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Notifications\JobOfferSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobOfferController extends Controller
{
    public function store(Request $request, JobApplication $application)
    {
        $agency = auth()->user()->agency;
        if ($application->job->agency_id !== $agency->id) {
            abort(403);
        }

        $request->validate([
            'salary' => 'required|numeric',
            'employment_type' => 'required|string',
            'start_date' => 'required|date',
            'location' => 'required|string',
            'benefits' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $offerNumber = 'OFF-' . strtoupper(Str::random(8));

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => $offerNumber,
            'salary' => $request->salary,
            'employment_type' => $request->employment_type,
            'start_date' => $request->start_date,
            'location' => $request->location,
            'benefits' => $request->benefits,
            'remarks' => $request->remarks,
            'status' => 'Draft',
        ]);

        return back()->with('success', 'Job offer draft created successfully.');
    }

    public function send(JobOffer $offer)
    {
        $agency = auth()->user()->agency;
        if ($offer->application->job->agency_id !== $agency->id) {
            abort(403);
        }

        $offer->update(['status' => 'Sent']);

        $offer->application->applicant->notify(new JobOfferSent($offer));

        return back()->with('success', 'Job offer sent to applicant.');
    }

    public function uploadPdf(Request $request, JobOffer $offer)
    {
        $agency = auth()->user()->agency;
        if ($offer->application->job->agency_id !== $agency->id) {
            abort(403);
        }

        $request->validate([
            'offer_letter' => 'required|file|mimes:pdf|max:5120',
        ]);

        $offer->clearMediaCollection('offer_letter');
        $offer->addMediaFromRequest('offer_letter')->toMediaCollection('offer_letter');

        return back()->with('success', 'Offer letter PDF uploaded successfully.');
    }
}
