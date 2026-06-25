<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardWorkExperience extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'job_title',
        'company_name',
        'location',
        'started_at',
        'ended_at',
        'is_current',
        'responsibilities',
        'achievements',
    ];

    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
        'is_current' => 'boolean',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }
}
