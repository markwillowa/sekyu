<?php

namespace App\Http\Requests\Pro\Agency;

use App\Enums\Pro\EmploymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $employee = $this->route('employee');
        $agency = auth('pro_agency')->user()?->agency;

        return $agency && $employee && (int) $employee->agency_id === (int) $agency->id;
    }

    public function rules(): array
    {
        $employee = $this->route('employee');

        return [
            'employee_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees', 'employee_no')->ignore($employee?->id),
            ],
            'employee_code' => ['nullable', 'string', 'max:255'],

            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:255'],

            'position' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'max:255'],
            'employment_status' => ['required', Rule::enum(EmploymentStatus::class)],
            'date_hired' => ['required', 'date'],
            'probation_end_date' => ['nullable', 'date', 'after_or_equal:date_hired'],

            'current_site' => ['nullable', 'string', 'max:255'],
            'current_shift' => ['nullable', 'string', 'max:255'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
            'salary_type_id' => [
                'nullable',
                'integer',
                Rule::exists('master_salary_types', 'id')->where('is_active', true),
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
