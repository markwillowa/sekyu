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
        Schema::create('guard_availabilities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guard_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('availability_status')->default('available');

            $table->date('available_from')->nullable();

            $table->boolean('available_for_day_shift')->default(false);
            $table->boolean('available_for_night_shift')->default(false);
            $table->boolean('available_for_roving')->default(false);
            $table->boolean('available_for_reliever')->default(false);

            $table->boolean('willing_to_work_overtime')->default(false);
            $table->boolean('willing_to_work_holidays')->default(false);
            $table->boolean('willing_to_relocate')->default(false);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique('guard_profile_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_availabilities');
    }
};
