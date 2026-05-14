<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Modules\KhaiBaoNgoaiTru\Http\Controllers\KhaiBaoNgoaiTruController;

// =============================================
// TRANG CHÍNH - Tự động phân chia theo role
// =============================================
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/', [KhaiBaoNgoaiTruController::class, 'index'])->name('index');
});

// =============================================
// ROUTE CHO ADMIN - Chỉ admin được truy cập
// =============================================
Route::middleware(['web', 'auth', 'admin'])
    ->prefix('admin/khai-bao-ngoai-tru')
    ->name('khai_bao_ngoai_tru.')
    ->group(function () {
        Route::get('/', [KhaiBaoNgoaiTruController::class, 'danhSach'])->name('index');
        Route::get('/tao-ky', [KhaiBaoNgoaiTruController::class, 'taoKyKhaiBao'])->name('tao_ky');
        Route::post('/tao-ky', [KhaiBaoNgoaiTruController::class, 'luuKyKhaiBao'])->name('ky_store');
        Route::get('/sua-ky/{id}', [KhaiBaoNgoaiTruController::class, 'suaKyKhaiBao'])->name('ky_edit');
        Route::put('/sua-ky/{id}', [KhaiBaoNgoaiTruController::class, 'capNhatKyKhaiBao'])->name('ky_update');
        Route::get('/create', [KhaiBaoNgoaiTruController::class, 'create'])->name('create');
        Route::post('/', [KhaiBaoNgoaiTruController::class, 'store'])->name('store');
        Route::get('/{id}', [KhaiBaoNgoaiTruController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [KhaiBaoNgoaiTruController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KhaiBaoNgoaiTruController::class, 'update'])->name('update');
        Route::delete('/{id}', [KhaiBaoNgoaiTruController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/duyet', [KhaiBaoNgoaiTruController::class, 'duyet'])->name('duyet');
        Route::get('/{id}/tu-choi', [KhaiBaoNgoaiTruController::class, 'tuChoi'])->name('tuChoi');
    });

// =============================================
// ROUTE CHO SINH VIÊN - Chỉ sinh viên được truy cập
// =============================================
Route::middleware(['web', 'auth'])
    ->prefix('sinh-vien/khai-bao-ngoai-tru')
    ->name('sinh_vien.')
    ->group(function () {
        Route::get('/', [KhaiBaoNgoaiTruController::class, 'sinhVienIndex'])->name('index');
        Route::post('/luu', [KhaiBaoNgoaiTruController::class, 'luuKhaiBao'])->name('luu');
    });

// =============================================
// KÍCH HOẠT KHAI BÁO - Tất cả user đã đăng nhập
// =============================================
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/khai-bao-ngoai-tru/kich-hoat/{tinTuc}', [KhaiBaoNgoaiTruController::class, 'kichHoatTuTin'])->name('khai_bao_ngoai_tru.kich_hoat');
    Route::get('/khai-bao-ngoai-tru/{tinTuc}', [KhaiBaoNgoaiTruController::class, 'formKhaiBao'])->name('sinh_vien.form_khai_bao');
});

// Route cũ để tương thích - chuyển hướng về route mới
Route::middleware(['web', 'auth'])->get('/sinh-vien-khai-bao', function () {
    return redirect()->route('sinh_vien.index');
})->name('sinh_vien_khai_bao');
