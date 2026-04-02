<?php

use Illuminate\Support\Facades\Route;
use Modules\DiemDanh\Http\Controllers\DiemDanhController;

Route::get('/diemdanh', [DiemDanhController::class, 'index']);
