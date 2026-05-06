<?php

use Illuminate\Support\Facades\Route;
use Modules\TinTuc\Http\Controllers\TinTucController;
use Modules\TinTuc\Http\Controllers\LoaiTinController;
use Modules\TinTuc\Http\Controllers\KhaiBaoNoiTruController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('tin-tuc/{id}/download', [TinTucController::class, 'download'])->name('tintuc.download');
    Route::get('tin-tuc/download-file', [TinTucController::class, 'downloadFile'])->name('tintuc.downloadFile');
    Route::resource('tin-tuc', TinTucController::class)->names('tintuc');
    Route::resource('loai-tin', LoaiTinController::class)->names('loaitin');
    Route::resource('khai-bao-noi-tru', KhaiBaoNoiTruController::class)->names('khai_bao_noi_tru');
    Route::get('khai-bao-noi-tru/kich-hoat/{tinTuc}', [KhaiBaoNoiTruController::class, 'kichHoatTuTin'])->name('khai_bao_noi_tru.kich_hoat');
    
    // Route cho sinh viên khai báo nội trú
    Route::get('khai-bao-noi-tru-sinh-vien', [KhaiBaoNoiTruController::class, 'khaiBaoSinhVien'])->name('khai_bao_noi_tru.sinh_vien');
    Route::post('khai-bao-noi-tru-sinh-vien/luu', [KhaiBaoNoiTruController::class, 'luuKhaiBao'])->name('khai_bao_noi_tru.luu');
    
    // Duyệt/Từ chối khai báo nội trú
    Route::get('khai-bao-noi-tru/{id}/duyet', [KhaiBaoNoiTruController::class, 'duyet'])->name('khai_bao_noi_tru.duyet');
    Route::get('khai-bao-noi-tru/{id}/tu-choi', [KhaiBaoNoiTruController::class, 'tuChoi'])->name('khai_bao_noi_tru.tuChoi');
});
