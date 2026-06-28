<?php

namespace App\Http\Controllers\Pro\Agency;

use App\Enums\Pro\EmploymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pro\Agency\StoreEmployeeOnboardingRequest;
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
}
