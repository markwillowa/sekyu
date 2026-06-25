<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GuardIdentification extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'guard_profile_id',
        'id_type',
        'id_number',
        'issued_at',
        'expires_at',
        'issuing_authority',
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
            ->addMediaCollection('identifications');
    }
}
