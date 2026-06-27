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
            $template = $agency->workflowTemplates()->first();

            JobPost::factory()
                ->count(5)
                ->create([
                    'agency_id' => $agency->id,
                    'workflow_template_id' => $template?->id,
                ]);
        }
    }
}
