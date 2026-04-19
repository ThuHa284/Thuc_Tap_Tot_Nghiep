<?php

namespace Modules\DiemDanh\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use Modules\DiemDanh\app\Models\Category;
use Modules\DiemDanh\Models\SavsoftUser; 
// Chút nữa làm chức năng quét xong ta sẽ use thêm SavsoftAttendance

class DiemDanhController extends Controller
{
    public function index()
    {
        return redirect()->route('diemdanh.create_event');
    }

    // 1. Mở trang Form nhập tên sự kiện
    public function createEvent()
    {
        // Không cần lấy dữ liệu gì, chỉ hiển thị form
        return view('diemdanh::create_event');
    }

    // 2. Xử lý Form: Lưu tên sự kiện vào Session và mở Camera
   public function storeEvent(Request $request)
{
    // Validate người dùng đã nhập tên sự kiện
    $request->validate(['event_name' => 'required|string|max:255']);

    $eventName = $request->input('event_name');

    // Tìm hoặc Tạo mới sự kiện trong bảng category.
    // firstOrCreate sẽ tìm 'category_name', nếu không có sẽ tạo mới và trả về ID.
    $category = Category::firstOrCreate(
        ['category_name' => $eventName]
    );

    // Lưu ID và Tên sự kiện vào Session với key rõ ràng, nhất quán
    session([
        'diemdanh_event_id'   => $category->cid,
        'diemdanh_event_name' => $eventName
    ]);
    
    // Sau khi tạo sự kiện, quay trở lại trang tạo sự kiện và hiển thị thông báo.
    // Các nút chức năng (Mở Camera, Tạo QR) sẽ hiện ra để người dùng chọn bước tiếp theo.
    // Điều này giúp luồng hoạt động rõ ràng hơn.
    return redirect()->route('diemdanh.create_event')->with('success', 'Đã tạo thành công!');
}

   // 3. Trang hiển thị Camera quét mã
    public function scanCamera(Request $request)
    {
        // Trục xuất về Form nếu cố tình vào trang này mà chưa chọn sự kiện
        if (!$request->session()->has('diemdanh_event_name')) {
            return redirect()->route('diemdanh.create_event')->with('error', 'Vui lòng chọn sự kiện trước khi quét!');
        }

        // Lấy tên sự kiện từ Session bằng key đã lưu ở bước trước
        $eventName = $request->session()->get('diemdanh_event_name');
        
        // Trả về View giao diện Camera và truyền tên sự kiện sang
        return view('diemdanh::scan', compact('eventName'));
    }

    // Hiển thị mã QR của sự kiện đang được chọn trong Session
    public function showEventQr(Request $request)
    {
        // Kiểm tra xem đã có sự kiện nào được chọn chưa
        if (!$request->session()->has('diemdanh_event_id')) {
            return redirect()->route('diemdanh.create_event')->with('error', 'Vui lòng chọn một sự kiện để tạo mã QR!');
        }

        $eventId = $request->session()->get('diemdanh_event_id');
        $eventName = $request->session()->get('diemdanh_event_name');

        // Dữ liệu để mã hóa vào QR code chính là ID của sự kiện
        $qrData = $eventId; 
        return view('diemdanh::show_qr', compact('eventName', 'qrData'));
    }

    public function saveAttendance(Request $request)
{
    try {
        $qrData = $request->input('qr_code');
        
        // 1. Lấy thông tin sự kiện từ Session
        $eventName = session('diemdanh_event_name', 'Sự kiện không xác định');
        $categoryID = session('diemdanh_event_id');

        // Nếu không có categoryID trong session (ví dụ: session hết hạn), trả về lỗi
        if (!$categoryID) {
            return response()->json(['status' => 'error', 'message' => 'Phiên làm việc đã hết hạn. Vui lòng tạo lại sự kiện.'], 419);
        }

        // 2. Tách chuỗi QR
        $parts = explode('_', $qrData);
        $studentId = $parts[0] ?? 'N/A';
        $studentName = str_replace('_', ' ', $parts[1] ?? 'N/A');

        DB::table('savsoft_attendance')->insert([
            'studentid'    => $studentId,
            'student_name' => $studentName,
            'course_name'  => $eventName,
            'cid'          => $categoryID, // SỬA LỖI: Dùng ID sự kiện từ Session
            'time1'        => now(),
            'info_staff'   => 'Cán bộ quản lý',
            'date_class'   => date('d-m-Y'),
            'subject'      => 'Điểm danh sự kiện',
            'class_id'     => 'EVENT_CTSV', // Gán nhãn để phân biệt với lớp học
            'faculty_id'   => 'CTSV',
            'point'        => 0
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Đã lưu: $studentName vào sự kiện $eventName"
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Lỗi lưu DB: ' . $e->getMessage()
        ], 500);
    }

}
}