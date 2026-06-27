<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowTemplateStep extends Model
{
    protected $fillable = [
        'workflow_template_id',
        'name',
        'type',
        'sort_order',
        'is_terminal',
    ];

    protected $casts = [
        'is_terminal' => 'boolean',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(WorkflowTemplate::class, 'workflow_template_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'current_workflow_step_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(JobApplicationHistory::class, 'workflow_step_id');
    }
}
