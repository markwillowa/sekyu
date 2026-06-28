<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterDepartment extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function positions(): HasMany
    {
        return $this->hasMany(MasterPosition::class, 'department_id');
    }

    public function activePositions(): HasMany
    {
        return $this->positions()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
