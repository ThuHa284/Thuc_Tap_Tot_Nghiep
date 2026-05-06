<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('khai_bao_noi_tru')) {
            Schema::create('khai_bao_noi_tru', function (Blueprint $table) {
                $table->id();
                $table->string('ho_ten');
                $table->string('mssv')->unique();
                $table->string('so_dien_thoai_sv', 20);
                $table->text('dia_chi_hien_tai');
                $table->string('ten_chu_tro', 255);
                $table->string('so_dien_thoai_chu_tro', 20);
                $table->date('ngay_vao_tro');
                $table->string('ghi_chu')->nullable();
                $table->integer('trang_thai')->default(1);
                $table->timestamps();
            });
        }

        Schema::table('tintuc', function (Blueprint $table) {
            if (!Schema::hasColumn('tintuc', 'khai_bao_ky')) {
                $table->unsignedTinyInteger('khai_bao_ky')->nullable()->after('is_khai_bao_noi_tru');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('khai_bao_noi_tru')) {
            Schema::dropIfExists('khai_bao_noi_tru');
        }

        Schema::table('tintuc', function (Blueprint $table) {
            if (Schema::hasColumn('tintuc', 'khai_bao_ky')) {
                $table->dropColumn('khai_bao_ky');
            }
        });
    }
};