<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSimple('master_genders', [
            'Male',
            'Female',
            'Prefer not to say',
        ]);

        $this->seedSimple('master_civil_statuses', [
            'Single',
            'Married',
            'Widowed',
            'Separated',
            'Annulled',
        ]);

        $this->seedSimple('master_relationships', [
            'Father',
            'Mother',
            'Spouse',
            'Sibling',
            'Child',
            'Relative',
            'Friend',
            'Guardian',
            'Other',
        ]);

        $this->seedSimple('master_employment_types', [
            'Full-time',
            'Part-time',
            'Contractual',
            'Reliever',
            'Probationary',
            'Project-based',
        ]);

        $this->seedSimple('master_shift_types', [
            'Day Shift',
            'Night Shift',
            'Rotating Shift',
            'Graveyard Shift',
            '12-Hour Shift',
            '24-Hour Shift',
            'Flexible Shift',
        ]);

        $this->seedSortable('master_skill_levels', [
            'Beginner',
            'Intermediate',
            'Advanced',
            'Expert',
        ]);

        $this->seedSortable('master_language_proficiencies', [
            'Basic',
            'Conversational',
            'Fluent',
            'Native',
        ]);

        $this->seedLanguages();

        $this->seedSkills();

        $this->seedSpecializations();

        $this->seedTrainingTypes();

        $this->seedLicenseTypes();

        $this->seedClearanceTypes();
        $this->seedIdentificationTypes();
        $this->seedWorkLocationTypes();
        $this->seedSalaryTypes();
        $this->seedJobStatuses();
        $this->seedLocations();
        $this->seedWorkflowStepNames();
    }

    private function seedSimple(string $table, array $names): void
    {
        foreach ($names as $index => $name) {
            $code = str($name)->slug('_');

            DB::table($table)->updateOrInsert(
                ['code' => $code],
                [
                    'code' => $code,
                    'name' => $name,
                    'description' => null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function seedSortable(string $table, array $names): void
    {
        $this->seedSimple($table, $names);
    }

    private function seedLanguages(): void
    {
        $languages = [
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Filipino', 'code' => 'fil'],
            ['name' => 'Cebuano', 'code' => 'ceb'],
            ['name' => 'Ilocano', 'code' => 'ilo'],
            ['name' => 'Hiligaynon', 'code' => 'hil'],
            ['name' => 'Waray', 'code' => 'war'],
            ['name' => 'Kapampangan', 'code' => 'pam'],
            ['name' => 'Bicolano', 'code' => 'bcl'],
            ['name' => 'Pangasinan', 'code' => 'pag'],
            ['name' => 'Chinese Mandarin', 'code' => 'zh'],
            ['name' => 'Japanese', 'code' => 'ja'],
            ['name' => 'Korean', 'code' => 'ko'],
            ['name' => 'Arabic', 'code' => 'ar'],
        ];

        foreach ($languages as $index => $language) {
            DB::table('master_languages')->updateOrInsert(
                ['code' => $language['code']],
                [
                    'code' => $language['code'],
                    'name' => $language['name'],
                    'description' => null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function seedSkills(): void
    {
        $skills = [
            ['Access Control', 'Security Operations'],
            ['CCTV Monitoring', 'Security Operations'],
            ['Patrol Operations', 'Security Operations'],
            ['Perimeter Security', 'Security Operations'],
            ['Gate Security', 'Security Operations'],
            ['Visitor Management', 'Security Operations'],
            ['Bag Inspection', 'Security Operations'],
            ['Search and Inspection', 'Security Operations'],
            ['Crowd Control', 'Security Operations'],
            ['Traffic Control', 'Security Operations'],
            ['Parking Security', 'Security Operations'],
            ['Incident Reporting', 'Administration'],
            ['Logbook Management', 'Administration'],
            ['Key Control', 'Administration'],
            ['Radio Communication', 'Communication'],
            ['Customer Assistance', 'Communication'],
            ['Conflict Resolution', 'Communication'],
            ['Emergency Response', 'Emergency and Safety'],
            ['Fire Safety', 'Emergency and Safety'],
            ['First Aid', 'Emergency and Safety'],
            ['Workplace Safety', 'Emergency and Safety'],
            ['Risk Assessment', 'Security Management'],
            ['Surveillance', 'Security Management'],
            ['Asset Protection', 'Security Management'],
            ['Alarm Response', 'Security Systems'],
            ['Metal Detector Operation', 'Security Systems'],
            ['Escort Duties', 'Special Operations'],
            ['VIP Protection', 'Special Operations'],
            ['Defensive Tactics', 'Special Operations'],
            ['Investigation Support', 'Special Operations'],
        ];

        foreach ($skills as $index => [$name, $category]) {
            $code = str($name)->slug('_');

            DB::table('master_skills')->updateOrInsert(
                ['code' => $code],
                [
                    'code' => $code,
                    'name' => $name,
                    'description' => "Category: $category",
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function seedSpecializations(): void
    {
        $names = [
            'Mall Security',
            'Bank Security',
            'Residential Security',
            'Subdivision Security',
            'Corporate Security',
            'Industrial Security',
            'Warehouse Security',
            'Construction Site Security',
            'Hospital Security',
            'Hotel Security',
            'School Security',
            'Campus Security',
            'Event Security',
            'Airport Security',
            'Port Security',
            'Government Facility Security',
            'Retail Security',
            'Executive Protection',
            'VIP Escort',
            'Cash Escort',
        ];

        $this->seedSimple('master_specializations', $names);
    }

    private function seedTrainingTypes(): void
    {
        $trainings = [
            ['Basic Security Guard Training', 'Mandatory'],
            ['Refresher Training', 'Mandatory'],
            ['Pre-Licensing Training', 'Mandatory'],
            ['Firearms Proficiency Training', 'Firearms'],
            ['VIP Protection Training', 'Specialized'],
            ['First Aid and Basic Life Support', 'Safety'],
            ['Fire Safety Training', 'Safety'],
            ['Emergency Response Training', 'Safety'],
            ['Crisis Management', 'Safety'],
            ['Customer Service Training', 'Soft Skills'],
            ['Occupational Safety and Health', 'Safety'],
            ['Crowd Management', 'Specialized'],
            ['Radio Communication Training', 'Communication'],
            ['CCTV Monitoring Training', 'Security Systems'],
        ];

        foreach ($trainings as $index => [$name, $category]) {
            $code = str($name)->slug('_');

            DB::table('master_training_types')->updateOrInsert(
                ['code' => $code],
                [
                    'code' => $code,
                    'name' => $name,
                    'description' => "Category: $category",
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function seedLicenseTypes(): void
    {
        $licenses = [
            ['Security Guard License', true],
            ['License to Exercise Security Profession', true],
            ['LESP Armed', true],
            ['LESP Unarmed', true],
            ['Firearms License', true],
            ['Driver License', true],
        ];

        foreach ($licenses as $index => [$name, $requiresExpiry]) {
            $code = str($name)->slug('_');

            DB::table('master_license_types')->updateOrInsert(
                ['code' => $code],
                [
                    'code' => $code,
                    'name' => $name,
                    'description' => null,
                    'requires_expiry' => $requiresExpiry,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function seedClearanceTypes(): void
    {
        $clearances = [
            ['NBI Clearance', true],
            ['Police Clearance', true],
            ['Barangay Clearance', true],
            ['Court Clearance', true],
            ['Mayor Clearance', true],
            ['Medical Clearance', true],
            ['Drug Test Clearance', true],
        ];

        foreach ($clearances as $index => [$name, $requiresExpiry]) {
            $code = str($name)->slug('_');

            DB::table('master_clearance_types')->updateOrInsert(
                ['code' => $code],
                [
                    'code' => $code,
                    'name' => $name,
                    'description' => null,
                    'requires_expiry' => $requiresExpiry,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function seedIdentificationTypes(): void
    {
        $identifications = [
            ['PhilID (National ID)', false],
            ['UMID', false],
            ['SSS ID', false],
            ['TIN ID', false],
            ['PhilHealth ID', false],
            ['Pag-IBIG ID', false],
            ['Passport', true],
            ['Driver\'s License', true],
            ['PRC ID', true],
            ['Voter\'s ID', false],
            ['Postal ID', true],
            ['Company ID', false],
            ['School ID', false],
        ];

        foreach ($identifications as $index => [$name, $requiresExpiry]) {
            $code = str($name)->slug('_');

            DB::table('master_identification_types')->updateOrInsert(
                ['code' => $code],
                [
                    'code' => $code,
                    'name' => $name,
                    'description' => null,
                    'requires_expiry' => $requiresExpiry,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    private function seedWorkLocationTypes(): void
    {
        $names = [
            'On-site',
            'Remote',
            'Hybrid',
            'Field-based',
        ];

        $this->seedSimple('master_work_location_types', $names);
    }

    private function seedSalaryTypes(): void
    {
        $names = [
            'Monthly',
            'Semi-monthly',
            'Weekly',
            'Daily',
            'Hourly',
        ];

        $this->seedSimple('master_salary_types', $names);
    }

    private function seedJobStatuses(): void
    {
        $names = [
            'Active',
            'Inactive',
            'Draft',
            'Cancelled',
            'On Hold',
            'Completed',
        ];

        $this->seedSimple('master_job_statuses', $names);
    }

    private function seedLocations(): void
    {
        $this->seedSimple('master_locations', [
            'Quezon City',
            'Manila',
            'Makati',
            'Pasig',
            'Taguig',
            'Cebu City',
            'Davao City',
            'Mandaluyong',
            'Alabang',
            'Iloilo City',
            'Bacolod City',
            'Cagayan de Oro',
            'Zamboanga City',
            'Baguio City',
            'Angeles City',
        ]);
    }

    private function seedWorkflowStepNames(): void
    {
        $this->seedSimple('master_workflow_step_names', [
            'Application Received',
            'Resume Screening',
            'Initial Interview',
            'Technical Assessment',
            'Background Check',
            'Medical Examination',
            'Drug Testing',
            'Panel Interview',
            'Client Interview',
            'Final Interview',
            'Job Offer',
            'Onboarding',
            'Training',
            'Deployment',
            'Rejected',
            'Withdrawn',
        ]);
    }
}
