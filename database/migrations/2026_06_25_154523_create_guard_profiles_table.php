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
        Schema::create('guard_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();

            $table->string('headline')->nullable();
            $table->text('summary')->nullable();

            $table->date('birth_date')->nullable();

            $table->foreignId('master_gender_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('master_civil_status_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('nationality')->default('Filipino');

            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_profiles');
    }
};
