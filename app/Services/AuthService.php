<?php

namespace App\Services;

use App\Constants\UserRole;
use App\Models\User;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(protected User $user) {}
    public function handleLoginUser(array $credentials): ServiceResult
    {
        try {
            $user = $this->user::where('email', $credentials['email'])->first();
            if (!$user) {
                throw new \Exception('Tài khoản không tồn tại');
            }
            if (!Hash::check($credentials['password'], $user->password)) {
                throw new \Exception('Mật khẩu không chính xác');
            }
            if ($user->role != UserRole::ADMIN->value) {
                throw new \Exception('Bạn không có quyền truy cập');
            }
            Auth::login($user);
            return ServiceResult::success('Đăng nhập thành công');
        } catch (\Exception $e) {
            return ServiceResult::error($e->getMessage(), null, $e);
        }
    }

    public function handleRegisterUser(array $data): ServiceResult
    {
        try {
            $user = $this->user::create([
                'name' => $data['username'],
                'email' => $data['username'],
                'password' => Hash::make($data['password']),
                'status' => 1,
                'role' => UserRole::CLIENT->value,
                'referrer_id' => $data['referrer_id'] ?? null,
            ]);

            Auth::login($user);

            return ServiceResult::success($user, 'Đăng ký tài khoản thành công!');
        } catch (\Exception $e) {
            return ServiceResult::error($e->getMessage());
        }
    }
}
