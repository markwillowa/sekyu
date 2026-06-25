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
        Schema::create('guard_emergency_contacts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guard_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->foreignId('master_relationship_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('mobile_number')->nullable();
            $table->string('alternate_mobile_number')->nullable();

            $table->string('email')->nullable();

            $table->text('address')->nullable();

            $table->boolean('is_primary')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_emergency_contacts');
    }
};
