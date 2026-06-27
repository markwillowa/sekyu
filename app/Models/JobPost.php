<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPost extends Model
{
    use HasFactory;
    protected $fillable = [
        'agency_id',
        'location_id',
        'title',
        'slug',
        'is_featured',
        'employment_type_id',
        'work_location_type_id',
        'city',
        'province',
        'country',
        'salary_min',
        'salary_max',
        'salary_type_id',
        'description',
        'requirements',
        'benefits',
        'vacancies',
        'job_status_id',
        'workflow_template_id',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'employment_type_id' => 'integer',
        'work_location_type_id' => 'integer',
        'salary_type_id' => 'integer',
        'job_status_id' => 'integer',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function employmentType()
    {
        return $this->belongsTo(MasterEmploymentType::class, 'employment_type_id');
    }

    public function status()
    {
        return $this->belongsTo(MasterJobStatus::class, 'job_status_id');
    }

    public function salaryType()
    {
        return $this->belongsTo(MasterSalaryType::class, 'salary_type_id');
    }

    public function workLocationType()
    {
        return $this->belongsTo(MasterWorkLocationType::class, 'work_location_type_id');
    }

    public function location()
    {
        return $this->belongsTo(MasterLocation::class, 'location_id');
    }

    public function workflowTemplate(): BelongsTo
    {
        return $this->belongsTo(WorkflowTemplate::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_jobs')->withTimestamps();
    }

    public function isSavedBy(User $user)
    {
        return $this->savedByUsers()->where('user_id', $user->id)->exists();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }
}
