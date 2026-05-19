<?php

namespace App\Services;

use App\Constants\SettingName;
use App\Models\Setting;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DepositService
{
    public function __construct(protected TransactionService $transactionService) {}

    /**
     * Get bank information from settings
     */
    public function getBankInfo(): ServiceResult
    {
        try {
            $bankInfo = [
                'bank_bin' => Setting::get(SettingName::BIN_BANK->value),
                'bank_number' => Setting::get(SettingName::ACCOUNT_NUMBER->value),
                'bank_name' => Setting::get(SettingName::ACCOUNT_NAME->value),
                'banking' => Setting::get(SettingName::BANKING->value),
            ];

            return ServiceResult::success($bankInfo);
        } catch (\Exception $e) {
            Log::error('DepositService::getBankInfo error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy thông tin ngân hàng', null, $e);
        }
    }

    /**
     * Get recent deposits for user
     */
    public function getRecentDeposits(?int $userId = null, int $limit = 10): ServiceResult
    {
        try {
            if (!$userId) {
                // Return empty collection for guest users
                return ServiceResult::success(collect());
            }

            return $this->transactionService->getRecentDeposits($userId, $limit);
        } catch (\Exception $e) {
            Log::error('DepositService::getRecentDeposits error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy lịch sử nạp tiền', null, $e);
        }
    }
}
