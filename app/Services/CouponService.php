<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Product;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Log;

class CouponService
{
    public function __construct(protected Coupon $coupon) {}

    /**
     * Validate coupon for user and amount
     */
    public function validateCoupon(string $code, int $userId, float $amount, ?Product $product = null): ServiceResult
    {
        try {
            $coupon = $this->coupon::where('code', trim($code))->first();

            if (!$coupon) {
                return ServiceResult::error('Mã giảm giá không tồn tại');
            }

            $user = \App\Models\User::find($userId);
            if (!$user) {
                return ServiceResult::error('Người dùng không tồn tại');
            }

            $validation = $coupon->canBeUsedBy($user, $amount, $product);

            if (!$validation['valid']) {
                return ServiceResult::error($validation['message']);
            }

            return ServiceResult::success($coupon, 'Mã giảm giá hợp lệ');
        } catch (\Exception $e) {
            Log::error('CouponService::validateCoupon error: ' . $e->getMessage());
            return ServiceResult::error('Không thể xác thực mã giảm giá', null, $e);
        }
    }

    /**
     * Calculate discount amount from coupon
     */
    public function calculateDiscount(string $code, float $amount): ServiceResult
    {
        try {
            $coupon = $this->coupon::where('code', trim($code))->first();

            if (!$coupon) {
                return ServiceResult::error('Mã giảm giá không tồn tại');
            }

            $discountAmount = $coupon->calculateDiscount($amount);

            return ServiceResult::success([
                'coupon' => $coupon,
                'discount_amount' => $discountAmount
            ]);
        } catch (\Exception $e) {
            Log::error('CouponService::calculateDiscount error: ' . $e->getMessage());
            return ServiceResult::error('Không thể tính toán giảm giá', null, $e);
        }
    }

    /**
     * Record coupon usage
     */
    public function recordCouponUsage(int $couponId, int $userId, float $discountAmount): ServiceResult
    {
        try {
            $coupon = $this->coupon::find($couponId);

            if (!$coupon) {
                return ServiceResult::error('Mã giảm giá không tồn tại');
            }

            // Increment usage count
            $coupon->incrementUsage();

            // Record usage
            CouponUsage::insert([
                'coupon_id' => $couponId,
                'user_id' => $userId,
                'transaction_id' => null,
                'discount_amount' => $discountAmount,
                'used_at' => now(),
            ]);

            return ServiceResult::success(null, 'Đã ghi nhận sử dụng mã giảm giá');
        } catch (\Exception $e) {
            Log::error('CouponService::recordCouponUsage error: ' . $e->getMessage());
            return ServiceResult::error('Không thể ghi nhận sử dụng mã giảm giá', null, $e);
        }
    }
}
