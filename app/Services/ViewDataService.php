<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use App\Constants\SettingName;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ViewDataService
{
    public function getCheckoutPaymentConfig(): array
    {
        $enabled = filter_var(
            Setting::get(SettingName::PAYPAL_ENABLED->value, '1'),
            FILTER_VALIDATE_BOOL,
            FILTER_NULL_ON_FAILURE
        );

        return [
            'paypal_enabled' => $enabled ?? true,
            'paypal_client_id' => Setting::get(SettingName::PAYPAL_CLIENT_ID->value, 'sb'),
            'paypal_currency' => Setting::get(SettingName::PAYPAL_CURRENCY->value, 'USD'),
            'paypal_environment' => Setting::get(SettingName::PAYPAL_ENVIRONMENT->value, 'sandbox'),
        ];
    }

    /**
     * Get recent orders for marquee display
     */
    public function getRecentOrdersForMarquee(int $limit = 20): ServiceResult
    {
        try {
            $recentOrders = Cache::remember('recent_orders_marquee', 300, function () use ($limit) {
                return Order::with(['user', 'product'])
                    ->completed()
                    ->latest()
                    ->take($limit)
                    ->get();
            });

            return ServiceResult::success($recentOrders);
        } catch (\Exception $e) {
            Log::error('ViewDataService::getRecentOrdersForMarquee error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy đơn hàng gần đây', null, $e);
        }
    }

    /**
     * Get popup content from settings
     */
    public function getPopupContent(): ServiceResult
    {
        try {
            $popupContent = Setting::get(SettingName::POPUP_CONTENT->value);

            return ServiceResult::success($popupContent);
        } catch (\Exception $e) {
            Log::error('ViewDataService::getPopupContent error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy nội dung popup', null, $e);
        }
    }
}
