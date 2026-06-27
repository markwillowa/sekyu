<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Agency extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'owner_id',
        'location_id',
        'name',
        'slug',
        'license_number',
        'email',
        'phone',
        'logo',
        'city',
        'province',
        'country',
        'is_verified',
        'is_active',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function location()
    {
        return $this->belongsTo(MasterLocation::class, 'location_id');
    }

    public function jobPosts(): HasMany
    {
        return $this->hasMany(JobPost::class);
    }

    public function activeJobPosts(): HasMany
    {
        return $this->hasMany(JobPost::class)->whereHas('status', function ($query) {
            $query->whereIn('code', ['published', 'active']);
        });
    }

    public function workflowTemplates(): HasMany
    {
        return $this->hasMany(WorkflowTemplate::class);
    }

    public function jobApplications(): HasManyThrough
    {
        return $this->hasManyThrough(JobApplication::class, JobPost::class, 'agency_id', 'job_id');
    }

    public function getActiveJobsCountAttribute(): int
    {
        return $this->activeJobPosts()->count();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo')
            ->singleFile();
    }
}
