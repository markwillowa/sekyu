<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardAvailability extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'availability_status',
        'available_from',
        'available_for_day_shift',
        'available_for_night_shift',
        'available_for_roving',
        'available_for_reliever',
        'willing_to_work_overtime',
        'willing_to_work_holidays',
        'willing_to_relocate',
        'notes',
    ];

    protected $casts = [
        'available_from' => 'date',
        'available_for_day_shift' => 'boolean',
        'available_for_night_shift' => 'boolean',
        'available_for_roving' => 'boolean',
        'available_for_reliever' => 'boolean',
        'willing_to_work_overtime' => 'boolean',
        'willing_to_work_holidays' => 'boolean',
        'willing_to_relocate' => 'boolean',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }
}
