<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('agency_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();

            $table->foreignId('employment_type_id')->nullable()->constrained('master_employment_types');
            $table->foreignId('work_location_type_id')->nullable()->constrained('master_work_location_types');

            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->default('Philippines');

            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->foreignId('salary_type_id')->nullable()->constrained('master_salary_types');

            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();

            $table->unsignedInteger('vacancies')->default(1);

            $table->foreignId('job_status_id')->nullable()->constrained('master_job_statuses');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
