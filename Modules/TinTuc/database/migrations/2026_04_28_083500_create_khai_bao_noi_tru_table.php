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
        Schema::create('khai_bao_noi_tru', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');                          // Họ tên sinh viên
            $table->string('mssv')->unique();                 // Mã số sinh viên
            $table->string('so_dien_thoai_sv', 20);          // Số điện thoại sinh viên
            $table->text('dia_chi_hien_tai');                 // Địa chỉ hiện tại
            $table->string('ten_chu_tro', 255);              // Tên chủ trọ
            $table->string('so_dien_thoai_chu_tro', 20);     // Số điện thoại chủ trọ
            $table->date('ngay_vao_tro');                     // Ngày vào ở trọ
            $table->string('ghi_chu')->nullable();           // Ghi chú
            $table->integer('trang_thai')->default(1);      // 1: Chờ duyệt, 2: Đã duyệt, 0: Từ chối
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khai_bao_noi_tru');
    }
};
