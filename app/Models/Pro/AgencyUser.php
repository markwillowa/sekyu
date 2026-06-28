<?php

namespace App\Models\Pro;

use App\Enums\Pro\AccountStatus;
use App\Models\Agency;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AgencyUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'pro_agency_users';

    protected $guard = 'pro_agency';

    protected $fillable = [
        'agency_id',
        'username',
        'password',
        'name',
        'role',
        'status',
        'force_password_change',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => AccountStatus::class,
        'password' => 'hashed',
        'force_password_change' => 'boolean',
        'password_changed_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
