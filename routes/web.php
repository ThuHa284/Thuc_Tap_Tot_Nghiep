<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use Modules\ThiTracNghiem\Http\Controllers\ThiTracNghiemController;
use Modules\DiemDanh\Http\Controllers\DiemDanhController;
use Modules\TinTuc\Http\Controllers\TinTucController;

// ================== LOGIN ==================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ================== PROTECTED ROUTES ==================
Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('home');
    })->name('home');

    // ================== MODULE THI TRẮC NGHIỆM ==================
    Route::prefix('thitracnghiem')->name('thitracnghiem.')->group(function () {
        Route::get('/', [ThiTracNghiemController::class, 'index'])->name('index');
    });

    // ================== MODULE ĐIỂM DANH ==================
    Route::prefix('diemdanh')->name('diemdanh.')->group(function () {
        Route::get('/', [DiemDanhController::class, 'index'])->name('index');
    });

    // ================== MODULE TIN TỨC ==================
    Route::prefix('tintuc')->name('tintuc.')->group(function () {
        Route::get('/', [TinTucController::class, 'index'])->name('index');
    });

    // ================== MODULE XÁC NHẬN SINH VIÊN ==================
    Route::prefix('xacnhansv')->name('xacnhansv.')->group(function () {
        Route::get('/', [\Modules\XacNhanSV\Http\Controllers\XacNhanSVController::class, 'index'])->name('index');
    });
});


// ================== ADMIN ONLY ==================
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', function () {
        return "Trang admin";
    });
});
