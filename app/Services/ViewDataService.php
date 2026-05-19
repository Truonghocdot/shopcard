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
