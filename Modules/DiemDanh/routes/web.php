<?php

use Illuminate\Support\Facades\Route;
use Modules\DiemDanh\Http\Controllers\DiemDanhController;

app('view')->addNamespace('diemdanh', module_path('DiemDanh', 'resources/views'));

Route::middleware('auth')->prefix('diemdanh')->name('diemdanh.')->group(function () {
    Route::get('/', [DiemDanhController::class, 'index'])->name('index');

    Route::get('/lich-su', [DiemDanhController::class, 'studentHistory'])->name('history');

    Route::get('/su-kien/tao-moi', [DiemDanhController::class, 'createEvent'])->name('create_event');
    Route::post('/su-kien/luu', [DiemDanhController::class, 'storeEvent'])->name('store_event');
    Route::get('/su-kien/chon/{category}', [DiemDanhController::class, 'selectEvent'])->name('select_event');

    Route::get('/quet-ma', [DiemDanhController::class, 'scanCamera'])->name('scan');
    Route::get('/su-kien/ma-qr', [DiemDanhController::class, 'showEventQr'])->name('show_qr');
    Route::get('/checkin/{event}', [DiemDanhController::class, 'studentCheckin'])->name('student_checkin');

    Route::get('/su-kien/chi-tiet/{category}', [DiemDanhController::class, 'showEventDetails'])->name('show_details');

    Route::post('/save-attendance', [DiemDanhController::class, 'saveAttendance'])->name('save');
});


