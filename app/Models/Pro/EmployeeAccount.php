<?php

namespace App\Models\Pro;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class EmployeeAccount extends Authenticatable
{
    use Notifiable;

    protected $table = 'pro_employee_accounts';

    protected $guard = 'pro_employee';

    protected $fillable = [
        'agency_id',
        'employee_id',
        'username',
        'password',
        'status',
        'expires_at',
        'force_password_change',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'locked_until' => 'datetime',
        'force_password_change' => 'boolean',
        'password' => 'hashed',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
