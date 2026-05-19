<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        $this->clearCategoryCaches($category);
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        $this->clearCategoryCaches($category);
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        $this->clearCategoryCaches($category);
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        $this->clearCategoryCaches($category);
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        $this->clearCategoryCaches($category);
    }

    /**
     * Clear all category-related caches
     */
    private function clearCategoryCaches(Category $category): void
    {
        try {
            // Clear all category caches
            CacheService::clearCategoryCaches($category->id);

            // Clear specific category cache by slug
            if ($category->slug) {
                CacheService::clearCategoryBySlug($category->slug);
            }

            Log::info("CategoryObserver: Cleared caches for category ID {$category->id}");
        } catch (\Exception $e) {
            Log::error("CategoryObserver: Error clearing caches - " . $e->getMessage());
        }
    }
}
