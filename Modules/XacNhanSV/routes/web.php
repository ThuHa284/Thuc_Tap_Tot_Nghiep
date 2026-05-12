<?php

use Illuminate\Support\Facades\Route;
use Modules\XacNhanSV\Http\Controllers\XacNhanSVController;
use Modules\XacNhanSV\Http\Controllers\CtsvAdminController;

Route::middleware(['auth'])->group(function () {

    // ===== SINH VIÊN =====
    Route::get('/xacnhansv', [XacNhanSVController::class, 'index'])
        ->name('xacnhansv.index');

    Route::get('/xacnhansv/form/{formid}', [XacNhanSVController::class, 'showForm'])
        ->name('xacnhansv.ctsv.form.show');

    Route::post('/xacnhansv/form/{formid}', [XacNhanSVController::class, 'store'])
        ->name('xacnhansv.ctsv.form.store');

    Route::get('/xacnhansv/my-requests', [XacNhanSVController::class, 'myRequests'])
        ->name('xacnhansv.ctsv.my-requests');

    Route::get('/xacnhansv/my-requests/{id}', [XacNhanSVController::class, 'show'])
        ->name('xacnhansv.ctsv.my-requests.show');

    // ✅ THÊM: Sinh viên xóa đơn khi chưa được duyệt
    Route::delete('/xacnhansv/my-requests/{id}', [XacNhanSVController::class, 'destroy'])
        ->name('xacnhansv.ctsv.destroy');

    // ===== ADMIN =====
    Route::prefix('xacnhansv/admin')->name('xacnhansv.ctsv.admin.')->group(function () {
        Route::get('/dashboard',               [CtsvAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/forms',                   [CtsvAdminController::class, 'forms'])->name('forms');
        Route::get('/forms/create',            [CtsvAdminController::class, 'createForm'])->name('forms.create');
        Route::post('/forms',                  [CtsvAdminController::class, 'storeForm'])->name('forms.store');
        Route::get('/forms/{formid}/edit',     [CtsvAdminController::class, 'editForm'])->name('forms.edit');
        Route::put('/forms/{formid}',          [CtsvAdminController::class, 'updateForm'])->name('forms.update');
        Route::delete('/forms/{formid}',       [CtsvAdminController::class, 'destroyForm'])->name('forms.destroy');
        Route::get('/requests',                [CtsvAdminController::class, 'requests'])->name('requests');
        Route::get('/requests/print-bulk',     [CtsvAdminController::class, 'printBulk'])->name('requests.print-bulk'); 
        Route::get('/requests/{id}',           [CtsvAdminController::class, 'showRequest'])->name('requests.show');
        Route::post('/requests/{id}/approve',  [CtsvAdminController::class, 'approveRequest'])->name('requests.approve');
        Route::post('/requests/{id}/reject',   [CtsvAdminController::class, 'rejectRequest'])->name('requests.reject');
        Route::post('/requests/{id}/printed',  [CtsvAdminController::class, 'markPrinted'])->name('requests.printed');
    });
});