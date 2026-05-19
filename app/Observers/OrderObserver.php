<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $this->clearOrderRelatedCaches($order);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Only clear cache if order is completed
        if ($order->status === 1) { // STATUS_COMPLETED
            $this->clearOrderRelatedCaches($order);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        $this->clearOrderRelatedCaches($order);
    }

    /**
     * Clear order-related caches (marquee and leaderboard)
     */
    private function clearOrderRelatedCaches(Order $order): void
    {
        try {
            // Clear recent orders marquee and leaderboard
            CacheService::clearOrderRelatedCaches();

            Log::info("OrderObserver: Cleared order-related caches for order ID {$order->id}");
        } catch (\Exception $e) {
            Log::error("OrderObserver: Error clearing caches - " . $e->getMessage());
        }
    }
}
