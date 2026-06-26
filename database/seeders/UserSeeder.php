<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@sekyu.com',
            ],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        $agency = User::updateOrCreate(
            [
                'email' => 'agency@sekyu.com',
            ],
            [
                'name' => 'Agency User',
                'password' => Hash::make('agency'),
                'email_verified_at' => now(),
            ]
        );
        $agency->assignRole('agency');

        $guard = User::updateOrCreate(
            [
                'email' => 'guard@sekyu.com',
            ],
            [
                'name' => 'Security Guard',
                'password' => Hash::make('guard'),
                'email_verified_at' => now(),
            ]
        );
        $guard->assignRole('guard');
    }
}
