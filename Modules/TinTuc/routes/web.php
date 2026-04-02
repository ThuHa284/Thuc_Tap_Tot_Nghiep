<?php

use Illuminate\Support\Facades\Route;
use Modules\TinTuc\Http\Controllers\TinTucController;

Route::get('/tintuc', [TinTucController::class, 'index']);
