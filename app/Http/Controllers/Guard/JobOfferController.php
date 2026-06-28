<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use App\Models\MasterJobOfferStatus;
use App\Notifications\JobOfferResponse;
use Illuminate\Http\Request;

class JobOfferController extends Controller
{
    public function accept(JobOffer $offer)
    {
        if ($offer->application->guard_id !== auth()->id()) {
            abort(403);
        }

        if ($offer->status?->code !== 'sent') {
            return back()->with('error', 'Only sent offers can be accepted.');
        }

        $acceptedStatus = MasterJobOfferStatus::where('code', 'accepted')->first();

        $offer->update([
            'status_id' => $acceptedStatus->id,
            'accepted_at' => now(),
        ]);

        $offer->application->job->agency->owner->notify(new JobOfferResponse($offer));

        return back()->with('success', 'Job offer accepted.');
    }

    public function decline(JobOffer $offer)
    {
        if ($offer->application->guard_id !== auth()->id()) {
            abort(403);
        }

        if ($offer->status?->code !== 'sent') {
            return back()->with('error', 'Only sent offers can be declined.');
        }

        $declinedStatus = MasterJobOfferStatus::where('code', 'declined')->first();

        $offer->update([
            'status_id' => $declinedStatus->id,
            'declined_at' => now(),
        ]);

        $offer->application->job->agency->owner->notify(new JobOfferResponse($offer));

        return back()->with('success', 'Job offer declined.');
    }

    public function downloadPdf(JobOffer $offer)
    {
        if ($offer->application->guard_id !== auth()->id()) {
            abort(403);
        }

        $media = $offer->getFirstMedia('offer_letter');

        if (!$media) {
            return back()->with('error', 'Offer letter PDF not available.');
        }

        return $media;
    }
}
