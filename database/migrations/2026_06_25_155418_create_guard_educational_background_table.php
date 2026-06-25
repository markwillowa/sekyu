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
        Schema::create('guard_educational_background', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guard_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('level');
            $table->string('school_name');
            $table->string('course_or_strand')->nullable();

            $table->string('field_of_study')->nullable();

            $table->year('started_year')->nullable();
            $table->year('ended_year')->nullable();

            $table->boolean('is_current')->default(false);

            $table->string('honors_or_awards')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_educational_background');
    }
};
