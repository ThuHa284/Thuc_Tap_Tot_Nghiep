<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChirpController;
use Modules\ThiTracNghiem\App\Http\Controllers\ThiTracNghiemController;
use Modules\DiemDanh\App\Http\Controllers\DiemDanhController;
use Modules\TinTuc\App\Http\Controllers\TinTucController;

Route::get('/', function () {
    return view('layouts.master');
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
