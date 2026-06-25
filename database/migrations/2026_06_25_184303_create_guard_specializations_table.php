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
        Schema::create('guard_specializations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guard_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('master_specialization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('years_of_experience')
                ->nullable();

            $table->boolean('primary')
                ->default(false);

            $table->text('description')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_specializations');
    }
};
