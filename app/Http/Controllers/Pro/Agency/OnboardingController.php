<?php

namespace App\Http\Controllers\Pro\Agency;

use App\Enums\Pro\AccountStatus;
use App\Enums\Pro\EmploymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pro\Agency\StoreEmployeeOnboardingRequest;
use App\Http\Requests\Pro\Agency\UpdateEmployeeRequest;
use App\Models\MasterSalaryType;
use App\Models\Pro\Employee;
use App\Models\Pro\EmployeeAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    public function index()
    {
        $agency = auth('pro_agency')->user()->agency;

        $employees = $agency->employees()
            ->with('account')
            ->latest()
            ->paginate(10);

        return view('pro.agency.onboarding.index', [
            'employees' => $employees,
            'activeAccountCount' => $agency->employees()->has('account')->count(),
        ]);
    }

    public function create()
    {
        $applicantProfiles = auth('pro_agency')->user()
            ->agency
            ->acceptedApplicantProfiles()
            ->get();

        return view('pro.agency.onboarding.create', [
            'nextEmployeeNo' => $this->nextEmployeeNumber(),
            'nextEmployeeUsername' => $this->nextEmployeeUsername(),
            'salaryTypes' => MasterSalaryType::where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id'),
            'employmentTypeOptions' => $this->employmentTypeOptions(),
            'employmentStatusOptions' => collect(EmploymentStatus::cases())
                ->mapWithKeys(fn (EmploymentStatus $status) => [
                    $status->value => str($status->name)->headline()->toString(),
                ]),
            'applicantProfiles' => $applicantProfiles,
            'applicantProfilePayload' => $applicantProfiles
                ->mapWithKeys(fn ($profile) => [
                    $profile->id => [
                        'first_name' => $profile->first_name,
                        'middle_name' => $profile->middle_name,
                        'last_name' => $profile->last_name,
                        'suffix' => $profile->suffix,
                    ],
                ]),
        ]);
    }

    public function store(StoreEmployeeOnboardingRequest $request)
    {
        $agency = auth('pro_agency')->user()->agency;
        $validated = $request->validated();
        $temporaryPassword = null;
        $employeeNumber = $validated['employee_no'] ?: $this->nextEmployeeNumber();
        $probationEndDate = $validated['probation_end_date']
            ?? Carbon::parse($validated['date_hired'])->addMonth()->toDateString();
        $salaryType = isset($validated['salary_type_id'])
            ? MasterSalaryType::find($validated['salary_type_id'])?->name
            : null;

        $employee = DB::transaction(function () use ($agency, $validated, $employeeNumber, $probationEndDate, $salaryType, &$temporaryPassword) {
            $employee = Employee::create([
                'agency_id' => $agency->id,
                'guard_profile_id' => $validated['guard_profile_id'],
                'employee_no' => $employeeNumber,
                'employee_code' => $validated['employee_code'] ?? null,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'suffix' => $validated['suffix'] ?? null,
                'nickname' => $validated['nickname'] ?? null,
                'position' => $validated['position'],
                'department' => $validated['department'],
                'employment_type' => $validated['employment_type'],
                'employment_status' => $validated['employment_status'],
                'date_hired' => $validated['date_hired'],
                'probation_end_date' => $probationEndDate,
                'current_site' => $validated['current_site'] ?? null,
                'current_shift' => $validated['current_shift'] ?? null,
                'basic_salary' => $validated['basic_salary'] ?? null,
                'salary_type' => $salaryType,
                'is_active' => true,
            ]);

            if ($validated['create_account'] ?? false) {
                $temporaryPassword = ($validated['temporary_password'] ?? null) ?: $this->generateTemporaryPassword();
                $username = ($validated['username'] ?? null) ?: $this->nextEmployeeUsername();

                $employee->account()->create([
                    'agency_id' => $agency->id,
                    'username' => $username,
                    'password' => $temporaryPassword,
                    'status' => 'active',
                    'force_password_change' => true,
                ]);
            }

            return $employee;
        });

        $redirect = redirect()
            ->route('pro.agency.onboarding.index')
            ->with('status', "{$employee->full_name} has been onboarded.");

        if ($employee->account) {
            $redirect->with('username', $employee->account->username);
        }

        if ($temporaryPassword) {
            $redirect->with('temporary_password', $temporaryPassword);
        }

        return $redirect;
    }

    public function edit(Employee $employee)
    {
        $agency = auth('pro_agency')->user()->agency;

        abort_unless((int) $employee->agency_id === (int) $agency->id, 403);

        return view('pro.agency.onboarding.edit', [
            'employee' => $employee->load('account', 'guardProfile'),
            'salaryTypes' => MasterSalaryType::where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id'),
            'selectedSalaryTypeId' => MasterSalaryType::where('name', $employee->salary_type)->value('id'),
            'employmentTypeOptions' => $this->employmentTypeOptions(),
            'employmentStatusOptions' => collect(EmploymentStatus::cases())
                ->mapWithKeys(fn (EmploymentStatus $status) => [
                    $status->value => str($status->name)->headline()->toString(),
                ]),
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        $isActive = $validated['is_active'] ?? false;
        $salaryType = isset($validated['salary_type_id'])
            ? MasterSalaryType::find($validated['salary_type_id'])?->name
            : null;

        $employee->update([
            'employee_code' => $validated['employee_code'] ?? null,
            'nickname' => $validated['nickname'] ?? null,
            'position' => $validated['position'],
            'department' => $validated['department'],
            'employment_type' => $validated['employment_type'],
            'employment_status' => $validated['employment_status'],
            'current_site' => $validated['current_site'] ?? null,
            'current_shift' => $validated['current_shift'] ?? null,
            'basic_salary' => $validated['basic_salary'] ?? null,
            'salary_type' => $salaryType,
            'is_active' => $isActive,
        ]);

        $employee->account?->update([
            'status' => $isActive
                ? AccountStatus::Active
                : AccountStatus::Inactive,
        ]);

        return redirect()
            ->route('pro.agency.onboarding.index')
            ->with('status', "{$employee->full_name} has been updated.");
    }

    public function resetPin(Employee $employee)
    {
        $agency = auth('pro_agency')->user()->agency;

        abort_unless((int) $employee->agency_id === (int) $agency->id, 403);

        $account = $employee->account;

        if (! $account) {
            return redirect()
                ->route('pro.agency.onboarding.index')
                ->with('error', "{$employee->full_name} does not have a portal account yet.");
        }

        $temporaryPassword = $this->generateTemporaryPassword();

        $account->forceFill([
            'password' => $temporaryPassword,
            'force_password_change' => true,
            'password_changed_at' => null,
        ])->save();

        return redirect()
            ->route('pro.agency.onboarding.index')
            ->with('status', "PIN reset for {$employee->full_name}.")
            ->with('username', $account->username)
            ->with('temporary_password', $temporaryPassword);
    }

    private function nextEmployeeNumber(): string
    {
        $nextId = (Employee::max('id') ?? 0) + 1;

        return 'EMP-'.now()->format('Y').'-'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }

    private function generateTemporaryPassword(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function nextEmployeeUsername(): string
    {
        $latestAccount = EmployeeAccount::query()
            ->latest('created_at')
            ->latest('id')
            ->first();

        $username = $latestAccount && ctype_digit($latestAccount->username)
            ? (string) ((int) $latestAccount->username + 1)
            : '1000500';

        while (EmployeeAccount::where('username', $username)->exists()) {
            $username = (string) ((int) $username + 1);
        }

        return $username;
    }

    private function employmentTypeOptions(): array
    {
        return [
            'full_time' => 'Full-time',
            'part_time' => 'Part-time',
            'contractual' => 'Contractual',
            'project_based' => 'Project-based',
        ];
    }
}
