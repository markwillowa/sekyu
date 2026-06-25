<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardFirearmQualification extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'is_firearm_qualified',
        'firearm_type',
        'permit_number',
        'issued_at',
        'expires_at',
        'issuing_authority',
        'training_provider',
        'training_completed_at',
        'verification_status',
        'verification_notes',
    ];

    protected $casts = [
        'is_firearm_qualified' => 'boolean',
        'issued_at' => 'date',
        'expires_at' => 'date',
        'training_completed_at' => 'date',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }
}
