<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('agency_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('guard_profile_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Employee Identity
            |--------------------------------------------------------------------------
            */

            $table->string('employee_no')
                ->unique();

            $table->string('employee_code')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Personal Information
            |--------------------------------------------------------------------------
            */

            $table->string('first_name');

            $table->string('middle_name')
                ->nullable();

            $table->string('last_name');

            $table->string('suffix')
                ->nullable();

            $table->string('nickname')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Employment
            |--------------------------------------------------------------------------
            */

            $table->string('position');

            $table->string('department');

            $table->string('employment_type');

            $table->string('employment_status')
                ->default('active');

            $table->date('date_hired');

            $table->date('probation_end_date')
                ->nullable();

            $table->date('date_regularized')
                ->nullable();

            $table->date('date_resigned')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Assignment
            |--------------------------------------------------------------------------
            */

            $table->string('current_site')
                ->nullable();

            $table->string('current_shift')
                ->nullable();

            $table->foreignId('supervisor_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Payroll
            |--------------------------------------------------------------------------
            */

            $table->decimal('basic_salary', 12, 2)
                ->nullable();

            $table->string('salary_type')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
