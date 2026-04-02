<?php

use Illuminate\Support\Facades\Route;
use Modules\ThiTracNghiem\Http\Controllers\ThiTracNghiemController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('thitracnghiems', ThiTracNghiemController::class)->names('thitracnghiem');
});
