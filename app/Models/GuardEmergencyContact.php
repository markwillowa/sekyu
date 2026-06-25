<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardEmergencyContact extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'name',
        'master_relationship_id',
        'mobile_number',
        'alternate_mobile_number',
        'email',
        'address',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }

    public function relationshipType(): BelongsTo
    {
        return $this->belongsTo(
            MasterRelationship::class,
            'master_relationship_id'
        );
    }
}
