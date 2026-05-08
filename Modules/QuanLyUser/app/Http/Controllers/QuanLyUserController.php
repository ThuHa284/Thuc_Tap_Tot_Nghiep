<?php

namespace Modules\QuanLyUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class QuanLyUserController extends Controller
{
    public function index()
    {
        // Lọc danh sách Admin (1) và CTV (-1)
        $users = User::whereIn('su', [1, -1])->orderBy('uid', 'desc')->get();
        return view('quanlyuser::index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'studentid'  => 'required|unique:savsoft_users,studentid',
            'first_name' => 'required',
            'last_name'  => 'required',
            'password'   => 'required',
            'role'       => 'required'
        ]);

        User::create([
            'studentid'  => $request->studentid,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'password'   => md5($request->password),
            'email'      => $request->studentid . '@stu.edu.vn',
            'gid'        => 1,
            'su'         => $request->role,
            'note'       => '',
        ]);

        return redirect()->route('quanlyuser.index')->with('success', 'Thêm thành công!');
    }

    // Hàm xóa để fix lỗi "Call to undefined method destroy()"
    public function destroy($uid)
    {
        $user = User::find($uid);
        if ($user) {
            $user->delete();
            return redirect()->route('quanlyuser.index')->with('success', 'Đã xóa!');
        }
        return redirect()->route('quanlyuser.index')->with('error', 'Không tìm thấy user!');
    }
}
