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
        Schema::create('guard_clearances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guard_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('master_clearance_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('clearance_number')->nullable();

            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();

            $table->string('issuing_office')->nullable();

            $table->string('verification_status')->default('pending');
            $table->text('verification_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_clearances');
    }
};
