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
        Schema::create('guard_work_experiences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guard_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('job_title');
            $table->string('position')->nullable();
            $table->string('company_name');
            $table->string('location')->nullable();

            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();

            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();

            $table->text('responsibilities')->nullable();
            $table->text('achievements')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_work_experiences');
    }
};
