<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->clearProductCaches($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->clearProductCaches($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->clearProductCaches($product);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        $this->clearProductCaches($product);
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        $this->clearProductCaches($product);
    }

    /**
     * Clear all product-related caches
     */
    private function clearProductCaches(Product $product): void
    {
        try {
            // Clear flash sale products cache
            CacheService::clearProductCaches($product->id);

            // Clear specific product cache by slug
            if ($product->slug) {
                CacheService::clearProductBySlug($product->slug);
            }

            // If product was sold (status changed), clear order marquee
            if ($product->status === Product::STATUS_SOLD) {
                CacheService::clearOrderRelatedCaches();
            }

            Log::info("ProductObserver: Cleared caches for product ID {$product->id}");
        } catch (\Exception $e) {
            Log::error("ProductObserver: Error clearing caches - " . $e->getMessage());
        }
    }
}
