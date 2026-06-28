<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\MasterJobOfferStatus;
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
            'employment_type_id' => 'required|exists:master_employment_types,id',
            'start_date' => 'required|date',
            'location_id' => 'required|exists:master_locations,id',
            'benefits' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $offerNumber = 'OFF-' . strtoupper(Str::random(8));
        $draftStatus = MasterJobOfferStatus::where('code', 'draft')->first();

        $offer = JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => $offerNumber,
            'salary' => $request->salary,
            'employment_type_id' => $request->employment_type_id,
            'start_date' => $request->start_date,
            'location_id' => $request->location_id,
            'benefits' => $request->benefits,
            'remarks' => $request->remarks,
            'status_id' => $draftStatus->id,
        ]);

        return back()->with('success', 'Job offer draft created successfully.');
    }

    public function send(JobOffer $offer)
    {
        $agency = auth()->user()->agency;
        if ($offer->application->job->agency_id !== $agency->id) {
            abort(403);
        }

        $sentStatus = MasterJobOfferStatus::where('code', 'sent')->first();
        $offer->update(['status_id' => $sentStatus->id]);

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
