<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Clear all product-related caches
     */
    public static function clearProductCaches(?int $productId = null): void
    {
        Cache::forget('products:flash_sale');

        if ($productId) {
            Cache::forget("product:slug:*");
            Cache::forget("products:related:{$productId}");
        } else {
            // Clear all product caches
            Cache::tags(['products'])->flush();
        }
    }

    /**
     * Clear specific product cache by slug
     */
    public static function clearProductBySlug(string $slug): void
    {
        Cache::forget("product:slug:{$slug}");
    }

    /**
     * Clear all category-related caches
     */
    public static function clearCategoryCaches(?int $categoryId = null): void
    {
        Cache::forget('categories:featured');
        Cache::forget('categories:all_with_children');

        if ($categoryId) {
            Cache::forget("category:slug:*");
        } else {
            Cache::tags(['categories'])->flush();
        }
    }

    /**
     * Clear specific category cache by slug
     */
    public static function clearCategoryBySlug(string $slug): void
    {
        Cache::forget("category:slug:{$slug}");
    }

    /**
     * Clear all news-related caches
     */
    public static function clearNewsCaches(?int $newsId = null): void
    {
        // Clear latest news cache
        Cache::forget('news:latest:8');
        Cache::forget('news:latest:3');

        // Clear published news pages
        for ($page = 1; $page <= 10; $page++) {
            Cache::forget("news:published:page:{$page}");
        }

        if ($newsId) {
            Cache::forget("news:related:{$newsId}");
        } else {
            Cache::tags(['news'])->flush();
        }
    }

    /**
     * Clear leaderboard caches
     */
    public static function clearLeaderboardCaches(): void
    {
        Cache::forget('leaderboard:top_spenders:10');
        Cache::forget('leaderboard:top_spenders:20');
        Cache::forget('leaderboard:top_spenders:50');
    }

    /**
     * Clear order-related caches
     */
    public static function clearOrderRelatedCaches(): void
    {
        Cache::forget('recent_orders_marquee');
        self::clearLeaderboardCaches();
    }

    /**
     * Clear banner caches
     */
    public static function clearBannerCaches(): void
    {
        Cache::forget('home_banners');
    }

    /**
     * Clear all application caches
     */
    public static function clearAllCaches(): void
    {
        self::clearProductCaches();
        self::clearCategoryCaches();
        self::clearNewsCaches();
        self::clearLeaderboardCaches();
        self::clearOrderRelatedCaches();
        self::clearBannerCaches();
    }
}
