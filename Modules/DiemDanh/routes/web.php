<?php
use Illuminate\Support\Facades\Route;
use Modules\DiemDanh\Http\Controllers\DiemDanhController;


app('view')->addNamespace('diemdanh', module_path('DiemDanh', 'resources/views'));

// Toàn bộ route của module DiemDanh sẽ nằm trong group này
Route::prefix('diemdanh')->name('diemdanh.')->group(function () {
    
    // 1. Dòng này tạo ra tên route là 'diemdanh.index' -> Cứu sống thanh Menu!
    Route::get('/', [DiemDanhController::class, 'index'])->name('index');

    // 2. Trang tạo sự kiện điểm danh (Tên sẽ tự động ghép thành 'diemdanh.create_event')
    Route::get('/su-kien/tao-moi', [DiemDanhController::class, 'createEvent'])->name('create_event');

    // 3. Xử lý lưu sự kiện và mở màn hình quét
    Route::post('/su-kien/luu', [DiemDanhController::class, 'storeEvent'])->name('store_event');

    // 4. Màn hình bật Camera
    Route::get('/quet-ma', [DiemDanhController::class, 'scanCamera'])->name('scan');

    // 5. Hiển thị mã QR của sự kiện để sinh viên quét
    Route::get('/su-kien/ma-qr', [DiemDanhController::class, 'showEventQr'])->name('show_qr');

    // Route này dùng để nhận dữ liệu từ Camera gửi về ngầm
    Route::post('/api/save-attendance', [DiemDanhController::class, 'saveAttendance'])->name('api.save');

});
?>
