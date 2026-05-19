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
                return ServiceResult::error(__('profile.user_not_found'));
            }

            $updateData = [];

            if (isset($data['email'])) {
                $updateData['email'] = $data['email'];
            }

            if (isset($data['phone'])) {
                $updateData['phone'] = $data['phone'];
            }

            if (!empty($data['new_password'])) {
                $updateData['password'] = Hash::make($data['new_password']);
            }

            $user->update($updateData);

            return ServiceResult::success($user, __('profile.update_success'));
        } catch (\Exception $e) {
            Log::error('UserService::updateProfile error: ' . $e->getMessage());
            return ServiceResult::error(__('profile.update_failed'), null, $e);
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
                return ServiceResult::error(__('profile.user_not_found'));
            }

            if (!Hash::check($currentPassword, $user->password)) {
                return ServiceResult::error(__('profile.current_password_wrong'));
            }

            $user->update(['password' => Hash::make($newPassword)]);

            return ServiceResult::success($user, __('profile.password_changed'));
        } catch (\Exception $e) {
            Log::error('UserService::changePassword error: ' . $e->getMessage());
            return ServiceResult::error(__('profile.password_change_failed'), null, $e);
        }
    }
}
