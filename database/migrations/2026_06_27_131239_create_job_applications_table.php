<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_id')
                ->constrained('job_posts')
                ->cascadeOnDelete();

            $table->foreignId('guard_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('current_workflow_step_id')
                ->nullable()
                ->constrained('workflow_template_steps')
                ->nullOnDelete();

            $table->text('cover_letter')
                ->nullable();

            $table->timestamp('applied_at');

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'job_id',
                'guard_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
