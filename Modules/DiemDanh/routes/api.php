<?php

use Illuminate\Support\Facades\Route;
use Modules\DiemDanh\Http\Controllers\DiemDanhController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('diemdanhs', DiemDanhController::class)->names('diemdanh');
});
