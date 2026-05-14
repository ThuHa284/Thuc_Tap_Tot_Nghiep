# 📄 Tài liệu Module XacNhanSV

> **Dự án:** Thực tập tốt nghiệp  
> **Module:** Xác Nhận Sinh Viên (XacNhanSV)  
> **Công nghệ:** Laravel 12 · PHP 8.2 · MySQL · Bootstrap 5  
> **Vị trí:** `Modules/XacNhanSV/`

---

## 1. Tổng quan

Module **XacNhanSV** là hệ thống xin giấy tờ xác nhận trực tuyến dành cho sinh viên Trường Đại học Công nghệ Sài Gòn (STU). Sinh viên có thể nộp các loại đơn xác nhận thông qua giao diện web thay vì đến trực tiếp Phòng Công tác Sinh viên (CTSV), giúp tiết kiệm thời gian và tối ưu quy trình xử lý hành chính.

---

## 2. Chức năng chính

### 2.1 Phía Sinh viên
| Chức năng | Mô tả |
|-----------|-------|
| Xem danh sách mẫu đơn | Hiển thị tất cả loại giấy tờ có thể xin |
| Nộp đơn online | Điền form và nộp đơn trực tuyến |
| Chọn hình thức nhận | Trực tiếp / Bưu điện (kèm địa chỉ) |
| Xem lịch sử đơn | Theo dõi tất cả đơn đã nộp |
| Xem chi tiết đơn | Xem trạng thái và nội dung từng đơn |

### 2.2 Phía Admin CTSV
| Chức năng | Mô tả |
|-----------|-------|
| Dashboard thống kê | Tổng quan số đơn theo từng trạng thái |
| Quản lý mẫu đơn | Thêm / Sửa / Xóa mẫu giấy tờ |
| Duyệt đơn | Duyệt hoặc từ chối đơn của sinh viên |
| In đơn | In đơn lẻ hoặc in hàng loạt nhiều đơn |
| Đánh dấu đã in | Cập nhật trạng thái sau khi in |

---

## 3. Các mẫu đơn & Ràng buộc nghiệp vụ

| Form ID | Tên mẫu đơn | Ràng buộc nộp |
|---------|-------------|---------------|
| 1 | Đơn xin xác nhận hoãn nghĩa vụ quân sự | Tối đa **1 lần/năm** |
| 2 | Đơn xin xác nhận khác | Phải hoàn tất đơn cũ trước khi nộp mới |
| 3 | Giấy xác nhận vay vốn sinh viên | Tối đa **1 lần/năm** |
| 4 | Giấy xác nhận không bị xử phạt hành chính | Tối đa **1 lần/6 tháng** |
| 5 | Giấy xác nhận ưu đãi LĐ-TBXH | Tối đa **1 lần/năm** |
| Dynamic | Mẫu đơn tự tạo (Admin tạo mới) | Không giới hạn |

---

## 4. Luồng xử lý đơn

```
[Sinh viên nộp đơn]
        │
        ▼
┌─────────────────┐
│  Chờ duyệt (0)  │
└────────┬────────┘
         │
    Admin xét duyệt
         │
    ┌────┴────┐
    ▼         ▼
┌────────┐  ┌──────────┐
│Đã duyệt│  │Từ chối   │
│  (1)   │  │  (2)     │
└────┬───┘  └──────────┘
     │
     ▼
Admin đánh dấu đã in
     │
     ▼
┌──────────┐
│ Đã in(3) │
└──────────┘
```

| Mã | Trạng thái | Mô tả |
|----|-----------|-------|
| 0 | ⏳ Chờ duyệt | Đơn vừa được nộp, chờ admin xét |
| 1 | ✅ Đã duyệt | Admin đã duyệt, chưa in |
| 2 | ❌ Từ chối | Admin từ chối kèm lý do |
| 3 | 🖨️ Đã in | Đơn đã được in và hoàn tất |

---

## 5. Cấu trúc thư mục

```
Modules/XacNhanSV/
│
├── Http/
│   └── Controllers/
│       ├── XacNhanSVController.php     # Xử lý phía sinh viên
│       └── CtsvAdminController.php     # Xử lý phía admin
│
├── Models/
│   ├── EtpForm.php                     # Model mẫu giấy tờ
│   ├── EtpFormDetail.php               # Model các trường trong mẫu
│   └── EtpFormStudent.php              # Model đơn đã nộp
│
├── routes/
│   └── web.php                         # Định nghĩa route
│
└── resources/
    └── views/ctsv/
        ├── admin/
        │   ├── dashboard.blade.php     # Trang thống kê admin
        │   ├── forms.blade.php         # Danh sách mẫu giấy tờ
        │   ├── form-create.blade.php   # Tạo mẫu mới
        │   ├── form-edit.blade.php     # Sửa mẫu
        │   ├── requests.blade.php      # Danh sách đơn
        │   ├── request-show.blade.php  # Chi tiết đơn
        │   └── print-bulk.blade.php    # In hàng loạt
        ├── forms/
        │   ├── form-1-nvqs.blade.php
        │   ├── form-2-xac-nhan-khac.blade.php
        │   ├── form-3-vay-von.blade.php
        │   ├── form-4-hanh-chinh.blade.php
        │   ├── form-5-ld-tbxh.blade.php
        │   └── form-dynamic.blade.php
        ├── show-forms/
        │   ├── form-1-nvqs.blade.php
        │   ├── form-2-xac-nhan-khac.blade.php
        │   ├── form-3-vay-von.blade.php
        │   ├── form-4-hanh-chinh.blade.php
        │   ├── form-5-ld-tbxh.blade.php
        │   └── form-dynamic.blade.php
        ├── index.blade.php             # Trang chủ sinh viên
        ├── my-requests.blade.php       # Lịch sử đơn
        └── show.blade.php              # Chi tiết đơn (sinh viên)
```

---

## 6. Danh sách Routes

### Sinh viên (`/xacnhansv`)
| Method | URL | Route Name | Mô tả |
|--------|-----|-----------|-------|
| GET | `/xacnhansv` | `xacnhansv.index` | Trang chủ, danh sách mẫu đơn |
| GET | `/xacnhansv/form/{formid}` | `xacnhansv.ctsv.form.show` | Hiển thị form nộp đơn |
| POST | `/xacnhansv/form/{formid}` | `xacnhansv.ctsv.form.store` | Nộp đơn |
| GET | `/xacnhansv/my-requests` | `xacnhansv.ctsv.my-requests` | Lịch sử đơn |
| GET | `/xacnhansv/my-requests/{id}` | `xacnhansv.ctsv.my-requests.show` | Chi tiết đơn |

### Admin (`/xacnhansv/admin`)
| Method | URL | Route Name | Mô tả |
|--------|-----|-----------|-------|
| GET | `/admin/dashboard` | `...dashboard` | Trang thống kê |
| GET | `/admin/forms` | `...forms` | Danh sách mẫu |
| GET | `/admin/forms/create` | `...forms.create` | Form tạo mẫu mới |
| POST | `/admin/forms` | `...forms.store` | Lưu mẫu mới |
| GET | `/admin/forms/{formid}/edit` | `...forms.edit` | Form sửa mẫu |
| PUT | `/admin/forms/{formid}` | `...forms.update` | Cập nhật mẫu |
| DELETE | `/admin/forms/{formid}` | `...forms.destroy` | Xóa mẫu |
| GET | `/admin/requests` | `...requests` | Danh sách đơn |
| GET | `/admin/requests/print-bulk` | `...requests.print-bulk` | In hàng loạt |
| GET | `/admin/requests/{id}` | `...requests.show` | Chi tiết đơn |
| POST | `/admin/requests/{id}/approve` | `...requests.approve` | Duyệt đơn |
| POST | `/admin/requests/{id}/reject` | `...requests.reject` | Từ chối đơn |
| POST | `/admin/requests/{id}/printed` | `...requests.printed` | Đánh dấu đã in |

> Tất cả routes đều yêu cầu middleware `auth` (đăng nhập).

---

## 7. Phân quyền

| `su` | Vai trò | Quyền |
|------|---------|-------|
| `1` | Admin CTSV | Quản lý mẫu, duyệt/từ chối/in đơn, xem tất cả đơn |
| `0` | Sinh viên | Nộp đơn, xem đơn của mình |
| `-1` | Hỗ trợ | Nộp đơn, xem đơn của mình |

> Admin **không thể nộp đơn**. Khi admin truy cập form nộp đơn, hệ thống tự redirect về trang chủ.

---

## 8. Cơ sở dữ liệu

### Bảng `etp_form` — Mẫu giấy tờ
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| `formid` | int, PK, auto | ID mẫu |
| `name` | varchar | Tên mẫu giấy tờ |
| `description` | text | Mô tả |
| `url` | varchar | Giấy tờ cần nộp kèm |
| `signtitle` | varchar | Chức danh người ký |
| `signname` | varchar | Tên người ký |
| `schoolid` | varchar | Mã trường |
| `schoolname` | varchar | Tên trường |

### Bảng `etp_form_detail` — Các trường trong mẫu
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| `id` | int, PK | ID |
| `formid` | int, FK | Liên kết mẫu |
| `label` | varchar | Nhãn trường |
| `fdetail_order` | int | Thứ tự hiển thị |

### Bảng `etp_form_student` — Đơn đã nộp
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| `id` | int, PK | ID đơn |
| `uid` | int, FK | ID sinh viên |
| `studentid` | varchar | Mã số sinh viên |
| `formid` | int, FK | Liên kết mẫu |
| `data` | json | Dữ liệu form đã điền |
| `status` | tinyint | Trạng thái (0/1/2/3) |
| `get_at` | varchar | Hình thức nhận |
| `ReceivingAddress` | varchar | Địa chỉ nhận (bưu điện) |
| `note` | text | Ghi chú / lý do từ chối |
| `date1` | date | Ngày bắt đầu (nếu có) |
| `date2` | date | Ngày kết thúc (nếu có) |
| `created_at` | timestamp | Ngày nộp đơn |
| `updated_at` | timestamp | Ngày cập nhật |

---

## 9. Hướng dẫn cài đặt

```bash
# 1. Đảm bảo module đã được đăng ký trong config/modules.php

# 2. Chạy migration
php artisan migrate

# 3. Chạy server
php artisan serve
```

Truy cập: [http://127.0.0.1:8000/xacnhansv](http://127.0.0.1:8000/xacnhansv)

---

## 10. Ghi chú phát triển

- Mật khẩu sinh viên được hash bằng `md5` (theo hệ thống hiện tại).
- Dữ liệu form lưu dạng `JSON` trong cột `data` của bảng `etp_form_student`.
- Timezone mặc định sử dụng `Asia/Ho_Chi_Minh`.
- Form 1–5 có template riêng biệt, các mẫu do admin tạo mới dùng `form-dynamic`.

---

