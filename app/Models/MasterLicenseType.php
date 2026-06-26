<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterLicenseType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'sort_order',
        'is_active',
        'requires_expiry',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'requires_expiry' => 'boolean',
    ];

    public function guardLicenses(): HasMany
    {
        return $this->hasMany(GuardLicense::class);
    }
}
