<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GuardEducationalBackground extends Model implements HasMedia
{
    use InteractsWithMedia;
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->singleFile();
    }
}
