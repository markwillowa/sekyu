<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJobStatus extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
