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
        Schema::table('guard_work_experiences', function (Blueprint $table) {
            if (!Schema::hasColumn('guard_work_experiences', 'position')) {
                $table->string('position')->nullable()->after('job_title');
            }
            if (!Schema::hasColumn('guard_work_experiences', 'description')) {
                $table->text('description')->nullable()->after('is_current');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guard_work_experiences', function (Blueprint $table) {
            $table->dropColumn(['position', 'description']);
        });
    }
};
