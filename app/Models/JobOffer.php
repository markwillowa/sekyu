<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class JobOffer extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'job_application_id',
        'offer_number',
        'salary',
        'employment_type',
        'start_date',
        'location',
        'benefits',
        'remarks',
        'status',
        'accepted_at',
        'declined_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
        'salary' => 'decimal:2',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }
}
