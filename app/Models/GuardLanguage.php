<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardLanguage extends Model
{
    protected $fillable = [
        'guard_profile_id',
        'master_language_id',
        'master_language_proficiency_id',
    ];

    public function guardProfile(): BelongsTo
    {
        return $this->belongsTo(GuardProfile::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(MasterLanguage::class, 'master_language_id');
    }

    public function proficiency(): BelongsTo
    {
        return $this->belongsTo(
            MasterLanguageProficiency::class,
            'master_language_proficiency_id'
        );
    }
}
