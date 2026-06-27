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
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();

            $table->foreignId('location_id')
                ->nullable()
                ->constrained('master_locations')
                ->nullOnDelete();

            $table->string('license_number')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('logo')->nullable();

            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->default('Philippines');

            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
