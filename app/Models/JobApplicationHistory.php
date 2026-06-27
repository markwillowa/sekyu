<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplicationHistory extends Model
{
    protected $fillable = [
        'job_application_id',
        'workflow_step_id',
        'updated_by',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function step(): BelongsTo
    {
        return $this->belongsTo(
            WorkflowTemplateStep::class,
            'workflow_step_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}
