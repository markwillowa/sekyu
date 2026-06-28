<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GuardWorkExperience extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'guard_profile_id',
        'job_title',
        'position',
        'company_name',
        'location',
        'started_at',
        'ended_at',
        'is_current',
        'description',
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->singleFile();
    }
}
