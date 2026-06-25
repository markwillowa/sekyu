<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GuardClearance extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'guard_profile_id',
        'master_clearance_type_id',
        'clearance_number',
        'issued_at',
        'expires_at',
        'issuing_office',
        'verification_status',
        'verification_notes',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('clearances');
    }

    public function clearanceType(): BelongsTo
    {
        return $this->belongsTo(
            MasterClearanceType::class,
            'master_clearance_type_id'
        );
    }
}
