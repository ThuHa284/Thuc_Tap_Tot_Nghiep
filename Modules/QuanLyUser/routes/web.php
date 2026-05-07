<?php

use Illuminate\Support\Facades\Route;
use Modules\QuanLyUser\Http\Controllers\QuanLyUserController;

Route::middleware(['auth'])->prefix('quanlyusers')->group(function () {
    Route::get('/', [QuanLyUserController::class, 'index'])->name('quanlyuser.index');
    Route::post('/store', [QuanLyUserController::class, 'store'])->name('quanlyuser.store');
    Route::delete('/delete/{uid}', [QuanLyUserController::class, 'destroy'])->name('quanlyuser.destroy');
});
