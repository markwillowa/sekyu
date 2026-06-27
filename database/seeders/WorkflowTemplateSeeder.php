<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Agency;
use App\Models\WorkflowTemplate;
use App\Models\WorkflowTemplateStep;
use Illuminate\Database\Seeder;

class WorkflowTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $agencies = Agency::all();

        foreach ($agencies as $agency) {
            $this->createStandardTemplate($agency);
            $this->createFastTemplate($agency);
        }
    }

    private function createStandardTemplate(Agency $agency)
    {
        $template = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Standard Guard Hiring',
            'description' => 'Comprehensive hiring process with multiple evaluation steps.',
            'is_default' => true,
        ]);

        $steps = [
            ['name' => 'Application Received', 'type' => 'normal', 'sort_order' => 1],
            ['name' => 'Resume Screening', 'type' => 'normal', 'sort_order' => 2],
            ['name' => 'Initial Interview', 'type' => 'interview', 'sort_order' => 3],
            ['name' => 'Background Check', 'type' => 'document_request', 'sort_order' => 4],
            ['name' => 'Final Interview', 'type' => 'interview', 'sort_order' => 5],
            ['name' => 'Medical Examination', 'type' => 'medical_exam', 'sort_order' => 6],
            ['name' => 'Deployment', 'type' => 'deployment', 'sort_order' => 7, 'is_terminal' => true],
        ];

        foreach ($steps as $step) {
            $template->steps()->create($step);
        }
    }

    private function createFastTemplate(Agency $agency)
    {
        $template = WorkflowTemplate::create([
            'agency_id' => $agency->id,
            'name' => 'Fast Track Hiring',
            'description' => 'Quick deployment process for urgent requirements.',
            'is_default' => false,
        ]);

        $steps = [
            ['name' => 'Application Received', 'type' => 'normal', 'sort_order' => 1],
            ['name' => 'Resume Screening', 'type' => 'normal', 'sort_order' => 2],
            ['name' => 'Deployment', 'type' => 'deployment', 'sort_order' => 3, 'is_terminal' => true],
        ];

        foreach ($steps as $step) {
            $template->steps()->create($step);
        }
    }
}
