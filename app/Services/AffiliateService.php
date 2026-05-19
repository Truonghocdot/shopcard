<?php

namespace App\Services;

use App\Models\AffiliateCommission;
use App\Models\Order;
use App\Models\Wallet;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AffiliateService
{
    public function __construct(
        protected AffiliateCommission $affiliateCommission,
        protected Wallet $wallet
    ) {}

    /**
     * Process affiliate commission for an order
     */
    public function processCommission(int $orderId): ServiceResult
    {
        try {
            DB::beginTransaction();

            $order = Order::with('user.referrer')->lockForUpdate()->find($orderId);

            if (!$order || !$order->user->referrer_id) {
                DB::rollBack();
                Log::info("AffiliateService: No referrer for order {$orderId}");
                return ServiceResult::success(null, 'No referrer to process');
            }

            // Check if commission already processed
            $existing = $this->affiliateCommission::where('order_id', $order->id)->first();
            if ($existing) {
                DB::rollBack();
                Log::warning("AffiliateService: Commission already processed for order {$order->id}");
                return ServiceResult::error('Commission already processed');
            }

            $commissionRate = 0.05; // 5%
            $commissionAmount = $order->final_amount * $commissionRate;

            // Create commission record
            $commission = $this->affiliateCommission::create([
                'referrer_id' => $order->user->referrer_id,
                'referred_user_id' => $order->user_id,
                'order_id' => $order->id,
                'order_amount' => $order->final_amount,
                'commission_amount' => $commissionAmount,
                'status' => 'pending',
            ]);

            // Add commission to referrer's wallet
            $referrerWallet = $this->wallet::where('user_id', $order->user->referrer_id)
                ->lockForUpdate()
                ->first();

            if ($referrerWallet) {
                $referrerWallet->increment('balance', $commissionAmount);

                $commission->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'notes' => "Hoa hồng 5% từ đơn hàng #{$order->order_number}"
                ]);

                Log::info("AffiliateService: Paid {$commissionAmount}đ to user {$order->user->referrer_id} for order {$order->id}");

                DB::commit();
                return ServiceResult::success($commission, 'Commission processed successfully');
            } else {
                $commission->update([
                    'status' => 'failed',
                    'notes' => 'Ví người giới thiệu không tồn tại'
                ]);

                Log::error("AffiliateService: Referrer wallet not found for user {$order->user->referrer_id}");

                DB::commit();
                return ServiceResult::error('Referrer wallet not found');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("AffiliateService::processCommission failed for order {$orderId}: " . $e->getMessage());
            return ServiceResult::error('Failed to process commission', null, $e);
        }
    }

    /**
     * Get commission statistics for a user
     */
    public function getUserCommissionStats(int $userId): ServiceResult
    {
        try {
            $stats = [
                'total_referrals' => \App\Models\User::where('referrer_id', $userId)->count(),
                'total_commission' => $this->affiliateCommission::where('referrer_id', $userId)
                    ->where('status', 'paid')
                    ->sum('commission_amount'),
                'pending_commission' => $this->affiliateCommission::where('referrer_id', $userId)
                    ->where('status', 'pending')
                    ->sum('commission_amount'),
            ];

            return ServiceResult::success($stats);
        } catch (\Exception $e) {
            Log::error("AffiliateService::getUserCommissionStats error: " . $e->getMessage());
            return ServiceResult::error('Failed to get commission stats', null, $e);
        }
    }
}
