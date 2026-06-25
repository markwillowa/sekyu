<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardEmploymentPreference extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'preferred_job_title',
        'preferred_location',
        'expected_salary_min',
        'expected_salary_max',
        'employment_type',
        'preferred_shift',
        'willing_to_relocate',
        'available_immediately',
        'available_from',
    ];

    protected $casts = [
        'expected_salary_min' => 'decimal:2',
        'expected_salary_max' => 'decimal:2',
        'willing_to_relocate' => 'boolean',
        'available_immediately' => 'boolean',
        'available_from' => 'date',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }
}
