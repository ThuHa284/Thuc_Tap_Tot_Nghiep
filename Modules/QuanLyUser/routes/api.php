<?php

use Illuminate\Support\Facades\Route;
use Modules\QuanLyUser\Http\Controllers\QuanLyUserController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('quanlyusers', QuanLyUserController::class)->names('quanlyuser');
});
