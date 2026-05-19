<?php

namespace App\Observers;

use App\Models\Banner;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;

class BannerObserver
{
    /**
     * Handle the Banner "created" event.
     */
    public function created(Banner $banner): void
    {
        $this->clearBannerCaches();
    }

    /**
     * Handle the Banner "updated" event.
     */
    public function updated(Banner $banner): void
    {
        $this->clearBannerCaches();
    }

    /**
     * Handle the Banner "deleted" event.
     */
    public function deleted(Banner $banner): void
    {
        $this->clearBannerCaches();
    }

    /**
     * Handle the Banner "restored" event.
     */
    public function restored(Banner $banner): void
    {
        $this->clearBannerCaches();
    }

    /**
     * Handle the Banner "force deleted" event.
     */
    public function forceDeleted(Banner $banner): void
    {
        $this->clearBannerCaches();
    }

    /**
     * Clear banner caches
     */
    private function clearBannerCaches(): void
    {
        try {
            CacheService::clearBannerCaches();

            Log::info("BannerObserver: Cleared banner caches");
        } catch (\Exception $e) {
            Log::error("BannerObserver: Error clearing caches - " . $e->getMessage());
        }
    }
}
