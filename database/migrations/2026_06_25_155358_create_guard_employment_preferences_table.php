<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guard_employment_preferences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guard_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('preferred_job_title')->nullable();
            $table->string('preferred_location')->nullable();

            $table->decimal('expected_salary_min', 10, 2)->nullable();
            $table->decimal('expected_salary_max', 10, 2)->nullable();

            $table->foreignId('master_employment_type_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('master_shift_type_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->boolean('willing_to_relocate')->default(false);
            $table->boolean('available_immediately')->default(false);

            $table->date('available_from')->nullable();

            $table->timestamps();

            $table->unique('guard_profile_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_employment_preferences');
    }
};
