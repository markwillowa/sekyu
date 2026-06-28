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
        'employment_type_id',
        'start_date',
        'location_id',
        'benefits',
        'remarks',
        'status_id',
        'accepted_at',
        'declined_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
        'salary' => 'decimal:2',
        'employment_type_id' => 'integer',
        'location_id' => 'integer',
        'status_id' => 'integer',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function employmentType(): BelongsTo
    {
        return $this->belongsTo(MasterEmploymentType::class, 'employment_type_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(MasterLocation::class, 'location_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterJobOfferStatus::class, 'status_id');
    }
}
