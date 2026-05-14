<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('savsoft_category', function (Blueprint $table) {
            if (!Schema::hasColumn('savsoft_category', 'attendance_start_at')) {
                $table->dateTime('attendance_start_at')->nullable()->after('training_points');
            }

            if (!Schema::hasColumn('savsoft_category', 'attendance_end_at')) {
                $table->dateTime('attendance_end_at')->nullable()->after('attendance_start_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('savsoft_category', function (Blueprint $table) {
            if (Schema::hasColumn('savsoft_category', 'attendance_end_at')) {
                $table->dropColumn('attendance_end_at');
            }

            if (Schema::hasColumn('savsoft_category', 'attendance_start_at')) {
                $table->dropColumn('attendance_start_at');
            }
        });
    }
};
