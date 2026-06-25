<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardContactDetail extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'mobile_number',
        'alternate_mobile_number',
        'email',
        'current_address',
        'city',
        'province',
        'region',
        'postal_code',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }
}
