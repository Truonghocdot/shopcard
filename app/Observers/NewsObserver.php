<?php

namespace App\Observers;

use App\Models\News;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;

class NewsObserver
{
    /**
     * Handle the News "created" event.
     */
    public function created(News $news): void
    {
        $this->clearNewsCaches($news);
    }

    /**
     * Handle the News "updated" event.
     */
    public function updated(News $news): void
    {
        $this->clearNewsCaches($news);
    }

    /**
     * Handle the News "deleted" event.
     */
    public function deleted(News $news): void
    {
        $this->clearNewsCaches($news);
    }

    /**
     * Handle the News "restored" event.
     */
    public function restored(News $news): void
    {
        $this->clearNewsCaches($news);
    }

    /**
     * Handle the News "force deleted" event.
     */
    public function forceDeleted(News $news): void
    {
        $this->clearNewsCaches($news);
    }

    /**
     * Clear all news-related caches
     */
    private function clearNewsCaches(News $news): void
    {
        try {
            // Clear all news caches
            CacheService::clearNewsCaches($news->id);

        } catch (\Exception $e) {
            Log::error("NewsObserver: Error clearing caches - " . $e->getMessage());
        }
    }
}
