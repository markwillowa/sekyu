<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agencyUser = User::where('email', 'agency@sekyu.com')->first();

        if ($agencyUser) {
            Agency::updateOrCreate(
                ['owner_id' => $agencyUser->id],
                [
                    'name' => 'Elite Security Services',
                    'slug' => Str::slug('Elite Security Services'),
                    'license_number' => 'PSA-2026-0001',
                    'email' => 'contact@elitesecurity.com',
                    'phone' => '09123456789',
                    'city' => 'Makati City',
                    'province' => 'Metro Manila',
                    'country' => 'Philippines',
                    'is_verified' => true,
                    'is_active' => true,
                ]
            );
        }
    }
}
