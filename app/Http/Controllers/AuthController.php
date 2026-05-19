<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(protected \App\Services\AuthService $authService) {}

    // Login Views
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle Login
    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'username' => ['required', 'string'],
                'password' => ['required'],
            ],
            [
                'username.required' => 'Vui lòng nhập tên đăng nhập.',
                'password.required' => 'Mật khẩu không được để trống.',
            ]
        );

        if (Auth::attempt(['name' => $request->username, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();

            // Checks status
            if (Auth::user()->status !== 1) {
                Auth::logout();
                return back()->withErrors(['username' => 'Tài khoản của bạn đã bị khóa.']);
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'username' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('username');
    }

    // Handle Register
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,name|alpha_num',
            'password' => 'required|string|min:8|confirmed',
            'referrer_id' => 'nullable|exists:users,id',
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.unique' => 'Tên đăng nhập này đã được sử dụng.',
            'username.alpha_num' => 'Tên đăng nhập chỉ được chứa chữ cái và số.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'referrer_id.exists' => 'Người giới thiệu không tồn tại.',
        ]);

        $result = $this->authService->handleRegisterUser([
            'username' => $request->username,
            'password' => $request->password,
            'referrer_id' => $request->referrer_id,
        ]);

        if ($result->isError()) {
            return back()->withErrors(['username' => $result->getMessage()]);
        }

        return redirect()->route('home')->with('success', $result->getMessage());
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
