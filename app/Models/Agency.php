<?php

namespace App\Models;

use App\Models\Pro\Employee;
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

    public function proUsers()
    {
        return $this->hasMany(\App\Models\Pro\AgencyUser::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function acceptedApplicantProfiles()
    {
        return GuardProfile::query()
            ->select('guard_profiles.*')
            ->whereHas('user.jobApplications', function ($query) {
                $query
                    ->whereHas('job', fn ($jobQuery) => $jobQuery->where('agency_id', $this->id))
                    ->whereHas('offers', function ($offerQuery) {
                        $offerQuery
                            ->whereNotNull('accepted_at')
                            ->whereHas('status', fn ($statusQuery) => $statusQuery->where('code', 'accepted'));
                    });
            })
            ->whereDoesntHave('employees')
            ->orderBy('last_name')
            ->orderBy('first_name');
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
