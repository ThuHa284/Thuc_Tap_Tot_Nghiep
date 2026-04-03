<?php

use Illuminate\Support\Facades\Route;
use Modules\TinTuc\Http\Controllers\TinTucController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('tintucs', TinTucController::class)->names('tintuc');
});
