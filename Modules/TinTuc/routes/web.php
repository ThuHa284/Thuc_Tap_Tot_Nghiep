<?php

use Illuminate\Support\Facades\Route;
use Modules\TinTuc\Http\Controllers\TinTucController;
use Modules\TinTuc\Http\Controllers\LoaiTinController;

Route::resource('tin-tuc', TinTucController::class)->names('tintuc');
Route::resource('loai-tin', LoaiTinController::class)->names('loaitin');
