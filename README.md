## Dự án

**Tên:** Ứng dụng web cho phòng công tác sinh viên.  
**Công nghệ:** Laravel 12, MySQL, Bootstrap 5  
**Công cụ:** GitHub

---

## 1. Mục tiêu

- Làm việc nhóm hiệu quả
- Tránh conflict code
- Quản lý source code rõ ràng bằng Git
- Phân chia công việc theo module

## 2. Chiến lược nhánh (Branch Strategy)

### 2.1 Nhánh chính

- `main`
  - Chứa code ổn định
  - Không code trực tiếp trên nhánh này

### 2.2 Nhánh phát triển

- `develop`
  - Tổng hợp code từ các thành viên
  - Test trước khi đưa vào `main`

### 2.3 Nhánh chức năng

feature/<ten-chuc-nang>

Ví dụ:

feature/login
feature/attendance
feature/exam
feature/news

Quy tắc:

- Mỗi chức năng → 1 branch riêng
- Làm việc trên branch của mình
- Sau khi hoàn thành → merge vào `develop`

## 3. Quy tắc Commit

### Format:

<type>: <mô tả>

### Các loại commit:

`type`:

- `feat` : Thêm mới tính năng.
- `fix` : Sửa lỗi (bug).
- `refactor` : Thay đổi cấu trúc code nhưng không đổi chức năng.
- `chore` : Việc lặt vặt (config, script, build, không ảnh hưởng logic chính).
- `docs` : Thay đổi tài liệu.
- `style` : Thay đổi format, indent, không đổi logic.
- `module`: tên module hoặc khu vực chính:
  - `auth`, `category`, `product`, `cart`, `order`, `common`, ...

### Ví dụ:

feat: thêm chức năng đăng nhập
feat: tạo trang điểm danh
fix: sửa lỗi không lưu dữ liệu sinh viên
refactor: tách controller

### Nguyên tắc commit

- Mỗi commit nên thể hiện **một thay đổi logic rõ ràng** (1 chức năng, 1 bug hoặc 1 nhóm chỉnh sửa nhỏ có liên quan).
- Tránh commit kiểu:
  - `fix`, `update`, `change`, `test`, `temp`, `commit lan 1`, ...
- Trước khi commit:
  - Đảm bảo **build không lỗi** (ví dụ: `dotnet build` chạy thành công).
  - Hạn chế commit code chưa chạy được trừ khi có lý do kỹ thuật rõ ràng (và ghi chú trong commit message).
- Không commit:
  - File build: `bin/`, `obj/`, `.vs/`, ...
  - File cá nhân: `*.user`, `*.suo`, `.DS_Store`,...
  - Thông tin nhạy cảm: mật khẩu, API key, connection string thật (sử dụng appsettings.Development + user secret hoặc biến môi trường).

## 4. Workflow làm việc

### Bước 1: Tạo branch

git checkout develop
git pull
git checkout -b feature/<ten-chuc-nang>

### Bước 2: Làm việc

git add .
git commit -m "feat: mô tả"
git push origin feature/<ten-chuc-nang>

### Bước 3: Merge

- Tạo Pull Request → `develop`
- Sau khi test ổn → merge vào `main`

## 5. Phân chia module

Ví dụ:

- Team 1: ...
- Team 2: Điểm danh
- Team 3: Thi trắc nghiệm
- Team 4: Tin tức

## 6. Database

- Dùng chung 1 database
- Không tự ý sửa cấu trúc bảng
- Nếu cần thay đổi → trao đổi với nhóm

## 7. Giao diện

- Dùng Bootstrap 5
- Dùng layout chung

@extends('layout')
@section('content')

## 8. Quy tắc chung

- Không code trực tiếp trên `main`
- Luôn tạo branch khi làm việc
- Commit rõ ràng
- Không sửa phần của người khác nếu chưa trao đổi
