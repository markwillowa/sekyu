<?php

namespace Database\Seeders;

use App\Models\MasterDepartment;
use App\Models\MasterPosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentPositionSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'code' => 'OPS',
                'name' => 'Operations',
                'positions' => [
                    ['code' => 'SUP', 'name' => 'Supervisor'],
                    ['code' => 'SG',  'name' => 'Security Guard'],
                    ['code' => 'LG',  'name' => 'Lady Guard'],
                    ['code' => 'DRV', 'name' => 'Driver'],
                    ['code' => 'DSP', 'name' => 'Dispatch'],
                ],
            ],
            [
                'code' => 'HR',
                'name' => 'Human Resources',
                'positions' => [
                    ['code' => 'MGR', 'name' => 'Manager'],
                    ['code' => 'SUP', 'name' => 'Supervisor'],
                    ['code' => 'REC', 'name' => 'Recruiter'],
                ],
            ],
            [
                'code' => 'ACC',
                'name' => 'Accounting and Billing',
                'positions' => [
                    ['code' => 'ACCNT', 'name' => 'Accountant'],
                ],
            ],
        ];

        foreach ($departments as $dept) {

            $department = MasterDepartment::firstOrCreate(
                [
                    'code' => $dept['code'],
                ],
                [
                    'name' => $dept['name'],
                ]
            );

            foreach ($dept['positions'] as $position) {

                MasterPosition::firstOrCreate(
                    [
                        'department_id' => $department->id,
                        'code' => $dept['code'] . '-' . $position['code'],
                    ],
                    [
                        'name' => $position['name'],
                    ]
                );
            }
        }
    }
}
