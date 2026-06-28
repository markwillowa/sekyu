<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardSpecialization extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'master_specialization_id',
        'years_of_experience',
        'primary',
        'description',
    ];

    protected $casts = [
        'years_of_experience' => 'integer',
        'primary' => 'boolean',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(
            MasterSpecialization::class,
            'master_specialization_id'
        );
    }
}
