<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('savsoft_category', function (Blueprint $table) {
            // Điểm rèn luyện: 1..3 (hoặc 0 khi sự kiện thuộc CTXH)
            if (!Schema::hasColumn('savsoft_category', 'training_points')) {
                $table->integer('training_points')->default(0)->after('ctxh_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('savsoft_category', function (Blueprint $table) {
            if (Schema::hasColumn('savsoft_category', 'training_points')) {
                $table->dropColumn('training_points');
            }
        });
    }
};

