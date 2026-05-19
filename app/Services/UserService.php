<?php

namespace App\Services;

use App\Models\User;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(protected User $user) {}

    /**
     * Update user profile
     */
    public function updateProfile(int $userId, array $data): ServiceResult
    {
        try {
            $user = $this->user::find($userId);

            if (!$user) {
                return ServiceResult::error('Người dùng không tồn tại');
            }

            $updateData = [];

            // Update email if provided
            if (isset($data['email'])) {
                $updateData['email'] = $data['email'];
            }

            // Update phone if provided
            if (isset($data['phone'])) {
                $updateData['phone'] = $data['phone'];
            }

            // Update password if provided
            if (!empty($data['new_password'])) {
                $updateData['password'] = Hash::make($data['new_password']);
            }

            $user->update($updateData);

            return ServiceResult::success($user, 'Cập nhật thông tin thành công');
        } catch (\Exception $e) {
            Log::error('UserService::updateProfile error: ' . $e->getMessage());
            return ServiceResult::error('Không thể cập nhật thông tin', null, $e);
        }
    }

    /**
     * Change password
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): ServiceResult
    {
        try {
            $user = $this->user::find($userId);

            if (!$user) {
                return ServiceResult::error('Người dùng không tồn tại');
            }

            if (!Hash::check($currentPassword, $user->password)) {
                return ServiceResult::error('Mật khẩu hiện tại không đúng');
            }

            $user->update(['password' => Hash::make($newPassword)]);

            return ServiceResult::success($user, 'Đổi mật khẩu thành công');
        } catch (\Exception $e) {
            Log::error('UserService::changePassword error: ' . $e->getMessage());
            return ServiceResult::error('Không thể đổi mật khẩu', null, $e);
        }
    }

    /**
     * Set transaction PIN (password2)
     */
    public function setTransactionPin(int $userId, string $pin): ServiceResult
    {
        try {
            $user = $this->user::find($userId);

            if (!$user) {
                return ServiceResult::error('Người dùng không tồn tại');
            }

            $user->forceFill(['password2' => $pin])->save();

            return ServiceResult::success($user, 'Thiết lập mã PIN thành công');
        } catch (\Exception $e) {
            Log::error('UserService::setTransactionPin error: ' . $e->getMessage());
            return ServiceResult::error('Không thể thiết lập mã PIN', null, $e);
        }
    }

    /**
     * Verify transaction PIN
     */
    public function verifyTransactionPin(int $userId, string $pin): ServiceResult
    {
        try {
            $user = $this->user::find($userId);

            if (!$user) {
                return ServiceResult::error('Người dùng không tồn tại');
            }

            if (!$user->password2) {
                return ServiceResult::error('Bạn chưa thiết lập mật khẩu cấp 2');
            }

            if ($user->password2 !== $pin) {
                return ServiceResult::error('Mật khẩu cấp 2 không đúng');
            }

            return ServiceResult::success(null, 'Xác thực thành công');
        } catch (\Exception $e) {
            Log::error('UserService::verifyTransactionPin error: ' . $e->getMessage());
            return ServiceResult::error('Không thể xác thực mã PIN', null, $e);
        }
    }
}
