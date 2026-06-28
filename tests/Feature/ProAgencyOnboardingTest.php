<?php

namespace Tests\Feature;

use App\Enums\Pro\AccountStatus;
use App\Enums\Pro\EmploymentStatus;
use App\Enums\Pro\UserRole;
use App\Models\Agency;
use App\Models\GuardProfile;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\JobPost;
use App\Models\MasterJobOfferStatus;
use App\Models\MasterSalaryType;
use App\Models\Pro\AgencyUser;
use App\Models\Pro\Employee;
use App\Models\Pro\EmployeeAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProAgencyOnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_agency_user_can_view_onboarding_pages(): void
    {
        $agencyUser = $this->agencyUser();
        $eligibleProfile = $this->acceptedApplicantProfile($agencyUser->agency);
        $salaryType = MasterSalaryType::create([
            'code' => 'daily',
            'name' => 'Daily',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $otherAgency = Agency::factory()->create();
        $ineligibleProfile = $this->acceptedApplicantProfile($otherAgency, [
            'first_name' => 'Not',
            'last_name' => 'Available',
        ]);

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->get(route('pro.agency.onboarding.index'))
            ->assertOk()
            ->assertSee('Employee Onboarding');

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->get(route('pro.agency.onboarding.create'))
            ->assertOk()
            ->assertSee('Onboard Employee')
            ->assertSee($eligibleProfile->full_name)
            ->assertSee($salaryType->name)
            ->assertDontSee($ineligibleProfile->full_name);
    }

    public function test_agency_user_can_onboard_employee_with_portal_account(): void
    {
        $agencyUser = $this->agencyUser();
        $profile = $this->acceptedApplicantProfile($agencyUser->agency);
        $salaryType = MasterSalaryType::create([
            'code' => 'monthly',
            'name' => 'Monthly',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($agencyUser, 'pro_agency')
            ->post(route('pro.agency.onboarding.store'), [
                'guard_profile_id' => $profile->id,
                'employee_no' => 'EMP-2026-00001',
                'employee_code' => 'SEC-001',
                'first_name' => $profile->first_name,
                'middle_name' => $profile->middle_name,
                'last_name' => $profile->last_name,
                'suffix' => $profile->suffix,
                'position' => 'Security Guard',
                'department' => 'Operations',
                'employment_type' => 'full_time',
                'employment_status' => EmploymentStatus::Probationary->value,
                'date_hired' => '2026-06-29',
                'current_site' => 'Makati Tower',
                'current_shift' => 'Day Shift',
                'basic_salary' => '25000',
                'salary_type_id' => $salaryType->id,
                'create_account' => '1',
                'temporary_password' => '123456',
            ]);

        $response
            ->assertRedirect(route('pro.agency.onboarding.index'))
            ->assertSessionHas('temporary_password', '123456')
            ->assertSessionHas('username', '1000500');

        $employee = Employee::first();

        $this->assertSame($agencyUser->agency_id, $employee->agency_id);
        $this->assertSame($profile->id, $employee->guard_profile_id);
        $this->assertSame($profile->full_name, $employee->full_name);
        $this->assertSame('Makati Tower', $employee->current_site);
        $this->assertSame('Monthly', $employee->salary_type);
        $this->assertSame(EmploymentStatus::Probationary->value, $employee->employment_status);
        $this->assertSame('2026-07-29', $employee->probation_end_date->toDateString());

        $account = EmployeeAccount::first();

        $this->assertSame($employee->id, $account->employee_id);
        $this->assertSame($agencyUser->agency_id, $account->agency_id);
        $this->assertSame('1000500', $account->username);
        $this->assertTrue(Hash::check('123456', $account->password));
        $this->assertTrue($account->force_password_change);
    }

    public function test_generated_employee_username_increments_latest_account_username(): void
    {
        $agencyUser = $this->agencyUser();
        $profile = $this->acceptedApplicantProfile($agencyUser->agency);
        $existingEmployee = Employee::create([
            'agency_id' => $agencyUser->agency_id,
            'employee_no' => 'EMP-2026-EXISTING',
            'first_name' => 'Existing',
            'last_name' => 'Employee',
            'position' => 'Security Guard',
            'department' => 'Operations',
            'employment_type' => 'full_time',
            'employment_status' => EmploymentStatus::Regular->value,
            'date_hired' => '2026-06-01',
            'is_active' => true,
        ]);

        $existingEmployee->account()->create([
            'agency_id' => $agencyUser->agency_id,
            'username' => '1000500',
            'password' => '111111',
            'status' => 'active',
            'force_password_change' => true,
        ]);

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->post(route('pro.agency.onboarding.store'), [
                'guard_profile_id' => $profile->id,
                'employee_no' => 'EMP-2026-00004',
                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,
                'position' => 'Security Guard',
                'department' => 'Operations',
                'employment_type' => 'full_time',
                'employment_status' => EmploymentStatus::Probationary->value,
                'date_hired' => '2026-06-29',
                'create_account' => '1',
            ])
            ->assertRedirect(route('pro.agency.onboarding.index'))
            ->assertSessionHas('username', '1000501');

        $this->assertDatabaseHas('pro_employee_accounts', [
            'username' => '1000501',
        ]);
    }

    public function test_agency_user_can_onboard_employee_without_portal_account(): void
    {
        $agencyUser = $this->agencyUser();
        $profile = $this->acceptedApplicantProfile($agencyUser->agency, [
            'first_name' => 'Juan',
            'middle_name' => null,
            'last_name' => 'Dela Cruz',
            'suffix' => null,
        ]);

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->post(route('pro.agency.onboarding.store'), [
                'guard_profile_id' => $profile->id,
                'employee_no' => 'EMP-2026-00002',
                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,
                'position' => 'Security Guard',
                'department' => 'Operations',
                'employment_type' => 'contractual',
                'employment_status' => EmploymentStatus::Contractual->value,
                'date_hired' => '2026-06-29',
                'create_account' => '0',
            ])
            ->assertRedirect(route('pro.agency.onboarding.index'));

        $this->assertDatabaseHas('employees', [
            'employee_no' => 'EMP-2026-00002',
            'agency_id' => $agencyUser->agency_id,
        ]);

        $this->assertDatabaseCount('pro_employee_accounts', 0);
    }

    public function test_agency_user_cannot_onboard_applicant_from_another_agency(): void
    {
        $agencyUser = $this->agencyUser();
        $otherAgency = Agency::factory()->create();
        $profile = $this->acceptedApplicantProfile($otherAgency);

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->post(route('pro.agency.onboarding.store'), [
                'guard_profile_id' => $profile->id,
                'employee_no' => 'EMP-2026-00003',
                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,
                'position' => 'Security Guard',
                'department' => 'Operations',
                'employment_type' => 'contractual',
                'employment_status' => EmploymentStatus::Contractual->value,
                'date_hired' => '2026-06-29',
                'create_account' => '0',
            ])
            ->assertSessionHasErrors('guard_profile_id');

        $this->assertDatabaseCount('employees', 0);
    }

    public function test_already_onboarded_applicant_is_not_available_for_onboarding(): void
    {
        $agencyUser = $this->agencyUser();
        $profile = $this->acceptedApplicantProfile($agencyUser->agency);

        Employee::create([
            'agency_id' => $agencyUser->agency_id,
            'guard_profile_id' => $profile->id,
            'employee_no' => 'EMP-2026-ONBOARDED',
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'position' => 'Security Guard',
            'department' => 'Operations',
            'employment_type' => 'full_time',
            'employment_status' => EmploymentStatus::Regular->value,
            'date_hired' => '2026-06-01',
            'is_active' => true,
        ]);

        $this
            ->actingAs($agencyUser, 'pro_agency')
            ->get(route('pro.agency.onboarding.create'))
            ->assertOk()
            ->assertDontSee($profile->full_name);
    }

    private function agencyUser(): AgencyUser
    {
        $agency = Agency::factory()->create();

        return AgencyUser::create([
            'agency_id' => $agency->id,
            'username' => 'agency-admin',
            'password' => 'password',
            'name' => 'Agency Admin',
            'role' => UserRole::Owner->value,
            'status' => AccountStatus::Active->value,
            'force_password_change' => false,
        ]);
    }

    private function acceptedApplicantProfile(Agency $agency, array $attributes = []): GuardProfile
    {
        $acceptedStatus = MasterJobOfferStatus::firstOrCreate(
            ['code' => 'accepted'],
            ['name' => 'Accepted', 'sort_order' => 3]
        );

        $applicant = User::factory()->create();

        $profile = GuardProfile::create(array_merge([
            'user_id' => $applicant->id,
            'first_name' => 'Maria',
            'middle_name' => 'Reyes',
            'last_name' => 'Santos',
            'suffix' => 'Jr.',
        ], $attributes));

        $jobPost = JobPost::create([
            'agency_id' => $agency->id,
            'title' => 'Security Guard',
            'slug' => 'security-guard-'.uniqid(),
            'description' => 'Security guard position.',
            'vacancies' => 1,
        ]);

        $application = JobApplication::create([
            'job_id' => $jobPost->id,
            'guard_id' => $applicant->id,
            'applied_at' => now(),
        ]);

        JobOffer::create([
            'job_application_id' => $application->id,
            'offer_number' => 'OFF-'.uniqid(),
            'salary' => 25000,
            'start_date' => now()->toDateString(),
            'status_id' => $acceptedStatus->id,
            'accepted_at' => now(),
        ]);

        return $profile;
    }
}
