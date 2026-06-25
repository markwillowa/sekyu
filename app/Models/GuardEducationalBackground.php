<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardEducationalBackground extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'level',
        'school_name',
        'course_or_strand',
        'field_of_study',
        'started_year',
        'ended_year',
        'is_current',
        'honors_or_awards',
        'description',
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }
}
