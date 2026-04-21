<?php

use Illuminate\Support\Facades\Route;
use Modules\ThiTracNghiem\Http\Controllers\ThiTracNghiemController;

Route::get('/thitracnghiem/login', [ThiTracNghiemController::class, 'showLoginForm'])->name('thitracnghiem.login.form');
Route::post('/thitracnghiem/login', [ThiTracNghiemController::class, 'login'])->name('thitracnghiem.login');
Route::post('/thitracnghiem/logout', [ThiTracNghiemController::class, 'logout'])->name('thitracnghiem.logout');

Route::get('/thitracnghiem/de-thi', [ThiTracNghiemController::class, 'quizList'])
    ->name('thitracnghiem.quiz.list');

Route::get('/thitracnghiem/de-thi/{quid}', [ThiTracNghiemController::class, 'quizShow'])
    ->name('thitracnghiem.quiz.show');
Route::get('/thitracnghiem/quy-che', [ThiTracNghiemController::class, 'quyChe'])
    ->name('thitracnghiem.quyche');

Route::get('/thitracnghiem/kiem-dinh', [ThiTracNghiemController::class, 'kiemDinh'])
    ->name('thitracnghiem.kiemdinh');

Route::get('/thitracnghiem/bieu-do-hoc-tap', [ThiTracNghiemController::class, 'bieuDoHocTap'])
    ->name('thitracnghiem.bieudo');

Route::get('/thitracnghiem/thong-tin-dao-tao', [ThiTracNghiemController::class, 'thongTinDaoTao'])
    ->name('thitracnghiem.thongtin');
Route::get('/thitracnghiem/de-thi/{quid}/bat-dau', [ThiTracNghiemController::class, 'quizStart'])
    ->name('thitracnghiem.quiz.start');
 Route::post('/quiz/submit/{quid}', [ThiTracNghiemController::class, 'submit'])
    ->name('thitracnghiem.quiz.submit');
Route::get('/thitracnghiem/lich-su-thi', [ThiTracNghiemController::class, 'history'])->name('thitracnghiem.history');
// Route fake để test không cần đăng nhập
Route::get('/fake', function() {
    session(['thi_trac_nghiem_user' => [
        'uid' => 1,
        'name' => 'Test User',
        'studentid' => 'TEST001',
        'email' => 'test@demo.com'
    ]]);
    return redirect()->route('thitracnghiem.quiz.list');
});