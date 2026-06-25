<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardSkill extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'name',
        'level',
        'years_of_experience',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }
}
