<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterLicenseType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'requires_expiry',
        'is_active',
    ];

    protected $casts = [
        'requires_expiry' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function guardLicenses(): HasMany
    {
        return $this->hasMany(GuardLicense::class);
    }
}
