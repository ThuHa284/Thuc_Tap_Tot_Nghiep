<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChirpController;

Route::get('/', function () {
    return view('layouts.master');
});


