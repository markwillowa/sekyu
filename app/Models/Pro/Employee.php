<?php

namespace App\Models\Pro;

use App\Models\Agency;
use App\Models\GuardProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [

        'agency_id',
        'guard_profile_id',

        'employee_no',
        'employee_code',

        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'nickname',

        'position',
        'department',
        'employment_type',
        'employment_status',

        'date_hired',
        'probation_end_date',
        'date_regularized',
        'date_resigned',

        'current_site',
        'current_shift',

        'supervisor_id',

        'basic_salary',
        'salary_type',

        'is_active',

    ];

    protected $casts = [

        'date_hired' => 'date',
        'probation_end_date' => 'date',
        'date_regularized' => 'date',
        'date_resigned' => 'date',

        'basic_salary' => 'decimal:2',

        'is_active' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function guardProfile()
    {
        return $this->belongsTo(GuardProfile::class);
    }

    public function account()
    {
        return $this->hasOne(EmployeeAccount::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(self::class, 'supervisor_id');
    }

    public function subordinates()
    {
        return $this->hasMany(self::class, 'supervisor_id');
    }

    public function getFullNameAttribute(): string
    {
        return Str::of(collect([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ])->filter()->implode(' '))->squish()->toString();
    }
}
