<?php

use Illuminate\Support\Facades\Route;
use Modules\XacNhanSV\Http\Controllers\XacNhanSVController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('xacnhansvs', XacNhanSVController::class)->names('xacnhansv');
});
