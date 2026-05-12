<?php

use Illuminate\Support\Facades\Route;
use Modules\KhaiBaoNgoaiTru\Http\Controllers\KhaiBaoNgoaiTruController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('khaibaongoaitrus', KhaiBaoNgoaiTruController::class)->names('khaibaongoaitru');
});
