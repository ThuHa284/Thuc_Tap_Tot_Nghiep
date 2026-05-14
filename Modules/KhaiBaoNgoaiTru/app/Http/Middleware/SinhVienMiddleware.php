<?php

namespace Modules\KhaiBaoNgoaiTru\app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SinhVienMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Kiểm tra đăng nhập
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }
        
        // Kiểm tra không phải admin (sinh viên)
        if ($user->is_admin == 1) {
            return redirect()->route('index')->with('error', 'Bạn không có quyền truy cập trang này.');
        }
        
        return $next($request);
    }
}
