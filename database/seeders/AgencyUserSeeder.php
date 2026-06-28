<?php

namespace Database\Seeders;

use App\Enums\Pro\AccountStatus;
use App\Enums\Pro\UserRole;
use App\Models\Agency;
use App\Models\Pro\AgencyUser;
use Illuminate\Database\Seeder;

class AgencyUserSeeder extends Seeder
{
    public function run(): void
    {
        $agency = Agency::first();

        AgencyUser::updateOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'agency_id' => $agency->id,

                'name' => 'System Administrator',

                'password' => '123456',

                'role' => UserRole::Owner,

                'status' => AccountStatus::Active,

                'force_password_change' => false,
            ]
        );
    }
}
