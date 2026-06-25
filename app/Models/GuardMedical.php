<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GuardMedical extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'guard_profile_id',
        'certificate_type',
        'clinic_or_hospital',
        'physician_name',
        'issued_at',
        'expires_at',
        'is_fit_to_work',
        'verification_status',
        'verification_notes',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
        'is_fit_to_work' => 'boolean',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('medical');
    }
}
