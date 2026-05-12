<?php

use Illuminate\Support\Facades\Route;
use Modules\KhaiBaoNgoaiTru\Http\Controllers\KhaiBaoNgoaiTruController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('khaibaongoaitrus', KhaiBaoNgoaiTruController::class)->names('khaibaongoaitru');
});
