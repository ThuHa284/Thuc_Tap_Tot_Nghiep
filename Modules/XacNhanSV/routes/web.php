<?php

use Illuminate\Support\Facades\Route;
use Modules\XacNhanSV\Http\Controllers\XacNhanSVController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('xacnhansvs', XacNhanSVController::class)->names('xacnhansv');
});
