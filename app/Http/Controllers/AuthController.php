<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'studentid' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('studentid', $request->studentid)->first();

        if ($user && $user->password === md5($request->password)) {
            Auth::login($user);

            $request->session()->regenerate();

            return redirect('/');
        }

        return back()->withErrors([
            'studentid' => 'Sai mã sinh viên hoặc mật khẩu'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
