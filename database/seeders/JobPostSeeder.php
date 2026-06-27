<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\JobPost;
use Illuminate\Database\Seeder;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agencies = Agency::all();

        foreach ($agencies as $agency) {
            JobPost::factory()
                ->count(10)
                ->create([
                    'agency_id' => $agency->id,
                ]);
        }
    }
}
