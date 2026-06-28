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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->string('offer_number')->unique();
            $table->decimal('salary', 12, 2);
            $table->string('employment_type'); // could be foreignId to master_employment_types
            $table->date('start_date');
            $table->string('location');
            $table->text('benefits')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->default('Draft'); // Draft, Sent, Accepted, Declined, Expired
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
