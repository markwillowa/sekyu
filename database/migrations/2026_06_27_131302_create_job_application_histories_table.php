<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_application_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_application_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('workflow_step_id')
                ->constrained('workflow_template_steps')
                ->cascadeOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')
                ->nullable();

            $table->timestamp('completed_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_application_histories');
    }
};
