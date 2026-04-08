<?php

use Illuminate\Support\Facades\Route;
use Modules\ThiTracNghiem\Http\Controllers\ThiTracNghiemController;

Route::get('/thitracnghiem', [ThiTracNghiemController::class, 'index'])->name('thitracnghiem.index');
