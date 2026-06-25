<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GuardCertification extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'guard_profile_id',
        'name',
        'issuing_organization',
        'issued_at',
        'expires_at',
        'credential_number',
        'credential_url',
        'description',
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
            ->addMediaCollection('certificates');
    }
}
