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
        Schema::create('guard_firearm_qualifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guard_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('is_firearm_qualified')->default(false);

            $table->string('firearm_type')->nullable();
            $table->string('permit_number')->nullable();

            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();

            $table->string('issuing_authority')->nullable();

            $table->string('training_provider')->nullable();
            $table->date('training_completed_at')->nullable();

            $table->string('verification_status')->default('pending');
            $table->text('verification_notes')->nullable();

            $table->timestamps();

            $table->unique('guard_profile_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_firearm_qualifications');
    }
};
