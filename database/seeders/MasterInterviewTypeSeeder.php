<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\MasterInterviewType;

class MasterInterviewTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['code' => 'on-site', 'name' => 'On-site', 'sort_order' => 1],
            ['code' => 'phone', 'name' => 'Phone', 'sort_order' => 2],
            ['code' => 'google-meet', 'name' => 'Google Meet', 'sort_order' => 3],
            ['code' => 'microsoft-teams', 'name' => 'Microsoft Teams', 'sort_order' => 4],
            ['code' => 'zoom', 'name' => 'Zoom', 'sort_order' => 5],
        ];

        foreach ($types as $type) {
            MasterInterviewType::updateOrCreate(
                ['code' => $type['code']],
                [
                    'name' => $type['name'],
                    'sort_order' => $type['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
