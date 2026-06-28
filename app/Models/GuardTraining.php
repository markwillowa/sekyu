<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GuardTraining extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'guard_profile_id',
        'master_training_type_id',
        'title',
        'training_provider',
        'started_at',
        'completed_at',
        'hours',
        'certificate_number',
        'description',
    ];

    protected $casts = [
        'started_at' => 'date',
        'completed_at' => 'date',
        'hours' => 'integer',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }

    public function trainingType(): BelongsTo
    {
        return $this->belongsTo(
            MasterTrainingType::class,
            'master_training_type_id'
        );
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('trainings');
    }
}
