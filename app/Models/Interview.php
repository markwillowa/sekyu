<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = [
        'job_application_id',
        'workflow_step_id',
        'interviewer_id',
        'interview_type_id',
        'title',
        'type',
        'scheduled_at',
        'duration_minutes',
        'location',
        'meeting_url',
        'notes',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
        'interview_type_id' => 'integer',
    ];

    public function interviewType(): BelongsTo
    {
        return $this->belongsTo(MasterInterviewType::class, 'interview_type_id');
    }

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function workflowStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowTemplateStep::class, 'workflow_step_id');
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}
