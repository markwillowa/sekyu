<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardReference extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'name',
        'position',
        'company',
        'relationship',
        'mobile_number',
        'email',
        'may_contact',
    ];

    protected $casts = [
        'may_contact' => 'boolean',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }
}
