<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pro_employee_accounts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('agency_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('username')->unique();

            $table->string('password');

            $table->timestamp('expires_at')
                ->nullable();

            $table->boolean('force_password_change')
                ->default(true);

            $table->timestamp('password_changed_at')
                ->nullable();

            $table->timestamp('last_login_at')
                ->nullable();

            $table->timestamp('locked_until')
                ->nullable();

            $table->string('status')
                ->default('active');

            $table->rememberToken();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pro_employee_accounts');
    }
};
