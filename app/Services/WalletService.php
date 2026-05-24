<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Log;

class WalletService
{
    public function __construct(protected Wallet $wallet) {}

    /**
     * Get user wallet
     */
    public function getUserWallet(int $userId): ServiceResult
    {
        try {
            $user = User::find($userId);
            if (! $user) {
                return ServiceResult::error('Người dùng không tồn tại');
            }

            $wallet = $user->ensureWallet();

            return ServiceResult::success($wallet);
        } catch (\Exception $e) {
            Log::error('WalletService::getUserWallet error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy thông tin ví', null, $e);
        }
    }

    /**
     * Deposit money to wallet
     */
    public function deposit(int $userId, float $amount): ServiceResult
    {
        try {
            $wallet = $this->wallet::firstOrCreate(
                ['user_id' => $userId],
                ['balance' => 0]
            );

            $wallet->deposit($amount);

            return ServiceResult::success($wallet, 'Nạp tiền thành công');
        } catch (\Exception $e) {
            Log::error('WalletService::deposit error: ' . $e->getMessage());
            return ServiceResult::error('Không thể nạp tiền vào ví', null, $e);
        }
    }

    /**
     * Withdraw money from wallet
     */
    public function withdraw(int $userId, float $amount): ServiceResult
    {
        try {
            $user = User::find($userId);
            if (! $user) {
                return ServiceResult::error('Người dùng không tồn tại');
            }

            $wallet = $this->wallet::where('user_id', $userId)->lockForUpdate()->first()
                ?? $user->ensureWallet();

            if ($wallet->balance < $amount) {
                return ServiceResult::error('Số dư không đủ');
            }

            $wallet->decrement('balance', $amount);

            return ServiceResult::success($wallet, 'Rút tiền thành công');
        } catch (\Exception $e) {
            Log::error('WalletService::withdraw error: ' . $e->getMessage());
            return ServiceResult::error('Không thể rút tiền từ ví', null, $e);
        }
    }

    /**
     * Check if wallet has sufficient balance
     */
    public function checkBalance(int $userId, float $requiredAmount): ServiceResult
    {
        try {
            $user = User::find($userId);
            if (! $user) {
                return ServiceResult::error('Người dùng không tồn tại');
            }

            $wallet = $user->ensureWallet();

            $hasSufficientBalance = $wallet->balance >= $requiredAmount;

            return ServiceResult::success([
                'has_sufficient_balance' => $hasSufficientBalance,
                'current_balance' => $wallet->balance,
                'required_amount' => $requiredAmount,
                'shortage' => $hasSufficientBalance ? 0 : ($requiredAmount - $wallet->balance)
            ]);
        } catch (\Exception $e) {
            Log::error('WalletService::checkBalance error: ' . $e->getMessage());
            return ServiceResult::error('Không thể kiểm tra số dư', null, $e);
        }
    }
}
