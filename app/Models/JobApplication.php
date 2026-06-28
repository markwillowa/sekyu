<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'guard_id',
        'current_workflow_step_id',
        'cover_letter',
        'applied_at',
        'completed_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPost::class, 'job_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guard_id');
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(
            WorkflowTemplateStep::class,
            'current_workflow_step_id'
        );
    }

    public function histories(): HasMany
    {
        return $this->hasMany(JobApplicationHistory::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class);
    }
}
