<?php

use Illuminate\Support\Facades\Route;

use Modules\ThiTracNghiem\Http\Controllers\ThiTracNghiemController;
use Modules\DiemDanh\Http\Controllers\DiemDanhController;
use Modules\TinTuc\Http\Controllers\TinTucController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Nhóm các Route dành cho Auth (Đã đăng nhập)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

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
