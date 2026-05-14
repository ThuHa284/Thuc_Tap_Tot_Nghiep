<?php

use Illuminate\Support\Facades\Route;
use Modules\TinTuc\Http\Controllers\TinTucController;
use Modules\TinTuc\Http\Controllers\LoaiTinController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('tin-tuc/{id}/download', [TinTucController::class, 'download'])->name('tintuc.download');
    Route::get('tin-tuc/download-file', [TinTucController::class, 'downloadFile'])->name('tintuc.downloadFile');
    Route::resource('tin-tuc', TinTucController::class)->names('tintuc');
    Route::resource('loai-tin', LoaiTinController::class)->names('loaitin');
});
