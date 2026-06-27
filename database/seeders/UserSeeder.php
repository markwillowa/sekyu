<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MasterGender;
use App\Models\MasterCivilStatus;
use App\Models\MasterLicenseType;
use App\Models\MasterSkill;
use App\Models\MasterSkillLevel;
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

        // Complete Admin's Guard Profile
        $this->seedGuardProfile($admin, [
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'headline' => 'Chief Security Operations & Systems Lead',
            'summary' => 'Seasoned security professional with 15+ years of experience in high-stakes environment protection and digital surveillance systems. Expertise in risk assessment, tactical response, and team leadership.',
        ]);

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
                'email' => 'applicant@sekyu.com',
            ],
            [
                'name' => 'Security Guard',
                'password' => Hash::make('applicant'),
                'email_verified_at' => now(),
            ]
        );
        $guard->assignRole('applicant');

        // Complete Guard's Profile
        $this->seedGuardProfile($guard, [
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'headline' => 'Certified Protection Officer (CPO) | Armed Security Specialist',
            'summary' => 'Highly disciplined Security Guard with a proven track record of maintaining safe and secure environments. Expert in access control, patrol procedures, and emergency response. Commended for vigilance and professional conduct.',
        ]);
    }

    private function seedGuardProfile(User $user, array $data): void
    {
        $gender = MasterGender::where('code', 'male')->first();
        $civilStatus = MasterCivilStatus::where('code', 'married')->first();

        $profile = $user->guardProfile()->updateOrCreate(
            ['user_id' => $user->id],
            array_merge([
                'first_name' => $data['first_name'],
                'middle_name' => 'Pro',
                'last_name' => $data['last_name'],
                'headline' => $data['headline'],
                'summary' => $data['summary'],
                'birth_date' => '1985-05-15',
                'master_gender_id' => $gender?->id,
                'master_civil_status_id' => $civilStatus?->id,
                'nationality' => 'Filipino',
            ], $data)
        );

        // Contact Details
        $profile->contactDetails()->updateOrCreate(
            ['guard_profile_id' => $profile->id],
            [
                'mobile_number' => '09123456789',
                'email' => $user->email,
                'current_address' => '123 Security Avenue, Defense Village',
                'city' => 'Quezon City',
                'province' => 'Metro Manila',
                'region' => 'NCR',
                'postal_code' => '1100',
            ]
        );

        // Physical Details
        $profile->physicalDetail()->updateOrCreate(
            ['guard_profile_id' => $profile->id],
            [
                'height_cm' => 178.00,
                'weight_kg' => 75.00,
                'body_type' => 'Athletic',
                'blood_type' => 'O+',
            ]
        );

        // Work Experiences
        $profile->workExperiences()->delete();
        $profile->workExperiences()->createMany([
            [
                'job_title' => 'Senior Security Officer',
                'company_name' => 'Global Shield Security',
                'location' => 'Makati CBD',
                'started_at' => '2020-01-01',
                'ended_at' => null,
                'is_current' => true,
                'responsibilities' => "Led a team of 10 guards in a corporate headquarters.\nManaged CCTV monitoring and access control systems.\nConducted regular site vulnerability assessments.",
            ],
            [
                'job_title' => 'Armed Security Guard',
                'company_name' => 'SafeHarbor Bank',
                'location' => 'BGC, Taguig',
                'started_at' => '2015-06-15',
                'ended_at' => '2019-12-31',
                'is_current' => false,
                'responsibilities' => "Provided armed security for high-value asset transport.\nMonitored bank premises for suspicious activities.\nAssisted customers and staff during emergencies.",
            ]
        ]);

        // Education
        $profile->educations()->delete();
        $profile->educations()->create([
            'level' => 'College',
            'school_name' => 'Philippine College of Criminology',
            'course_or_strand' => 'BS in Criminology',
            'started_year' => 2004,
            'ended_year' => 2008,
            'description' => 'Graduated with honors. Active member of the Security Management Society.',
        ]);

        // Licenses
        $licenseType = MasterLicenseType::where('code', 'security_guard_license')->first();
        $profile->licenses()->delete();
        $profile->licenses()->create([
            'master_license_type_id' => $licenseType?->id,
            'license_number' => 'SG-123456789',
            'issued_at' => '2023-01-01',
            'expires_at' => '2026-01-01',
            'issuing_authority' => 'PNP-SOSIA',
        ]);

        // Certifications
        $profile->certifications()->delete();
        $profile->certifications()->createMany([
            [
                'name' => 'Certified Protection Officer (CPO)',
                'issuing_organization' => 'IFPO',
                'issued_at' => '2021-05-20',
                'description' => 'International certification for professional protection officers.',
            ],
            [
                'name' => 'First Aid and CPR Certified',
                'issuing_organization' => 'Philippine Red Cross',
                'issued_at' => '2023-10-10',
                'expires_at' => '2025-10-10',
            ]
        ]);

        // Skills
        $skills = MasterSkill::limit(3)->get();
        $skillLevel = MasterSkillLevel::where('code', 'advanced')->first();
        $profile->skills()->delete();
        foreach ($skills as $skill) {
            $profile->skills()->create([
                'master_skill_id' => $skill->id,
                'master_skill_level_id' => $skillLevel?->id,
                'years_of_experience' => 5,
            ]);
        }
    }
}
