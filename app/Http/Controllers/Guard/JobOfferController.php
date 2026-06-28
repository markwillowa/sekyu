<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use App\Notifications\JobOfferResponse;
use Illuminate\Http\Request;

class JobOfferController extends Controller
{
    public function accept(JobOffer $offer)
    {
        if ($offer->application->guard_id !== auth()->id()) {
            abort(403);
        }

        if ($offer->status !== 'Sent') {
            return back()->with('error', 'Only sent offers can be accepted.');
        }

        $offer->update([
            'status' => 'Accepted',
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

        if ($offer->status !== 'Sent') {
            return back()->with('error', 'Only sent offers can be declined.');
        }

        $offer->update([
            'status' => 'Declined',
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
