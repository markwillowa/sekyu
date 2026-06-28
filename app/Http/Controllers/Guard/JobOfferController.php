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

        $acceptedStatus = $this->status('accepted', 'Accepted', 3);

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

        $declinedStatus = $this->status('declined', 'Declined', 4);

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

    private function status(string $code, string $name, int $sortOrder): MasterJobOfferStatus
    {
        return MasterJobOfferStatus::firstOrCreate(
            ['code' => $code],
            [
                'name' => $name,
                'sort_order' => $sortOrder,
                'is_active' => true,
            ]
        );
    }
}
