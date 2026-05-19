<?php

namespace App\Utilts;

use Illuminate\Support\Facades\Cache;

class Caching
{
    /**
     * Default cache TTL in seconds (1 hour)
     */
    const DEFAULT_TTL = 3600;

    /**
     * Cache key prefix
     */
    const PREFIX = 'shopfco_';

    /**
     * Get cache key with prefix
     */
    private static function getKey(string $key): string
    {
        return self::PREFIX . $key;
    }

    /**
     * Get value from cache
     */
    public static function get(string $key, $default = null)
    {
        return Cache::get(self::getKey($key), $default);
    }

    /**
     * Store value in cache
     */
    public static function put(string $key, $value, ?int $ttl = null): bool
    {
        $ttl = $ttl ?? self::DEFAULT_TTL;
        return Cache::put(self::getKey($key), $value, $ttl);
    }

    /**
     * Store value in cache forever
     */
    public static function forever(string $key, $value): bool
    {
        return Cache::forever(self::getKey($key), $value);
    }

    /**
     * Remember value in cache (get or store)
     */
    public static function remember(string $key, callable $callback, ?int $ttl = null)
    {
        $ttl = $ttl ?? self::DEFAULT_TTL;
        return Cache::remember(self::getKey($key), $ttl, $callback);
    }

    /**
     * Remember value in cache forever
     */
    public static function rememberForever(string $key, callable $callback)
    {
        return Cache::rememberForever(self::getKey($key), $callback);
    }

    /**
     * Check if key exists in cache
     */
    public static function has(string $key): bool
    {
        return Cache::has(self::getKey($key));
    }

    /**
     * Delete value from cache
     */
    public static function forget(string $key): bool
    {
        return Cache::forget(self::getKey($key));
    }

    /**
     * Increment value in cache
     */
    public static function increment(string $key, int $value = 1): int|bool
    {
        return Cache::increment(self::getKey($key), $value);
    }

    /**
     * Decrement value in cache
     */
    public static function decrement(string $key, int $value = 1): int|bool
    {
        return Cache::decrement(self::getKey($key), $value);
    }

    /**
     * Flush all cache
     */
    public static function flush(): bool
    {
        return Cache::flush();
    }

    /**
     * Flush cache by pattern
     */
    public static function flushByPattern(string $pattern): void
    {
        $keys = Cache::get('cache_keys', []);
        foreach ($keys as $key) {
            if (str_contains($key, $pattern)) {
                Cache::forget($key);
            }
        }
    }

    /**
     * Cache user data
     */
    public static function cacheUser(int $userId, $data, int $ttl = 3600): bool
    {
        return self::put("user_{$userId}", $data, $ttl);
    }

    /**
     * Get cached user data
     */
    public static function getCachedUser(int $userId)
    {
        return self::get("user_{$userId}");
    }

    /**
     * Forget cached user data
     */
    public static function forgetUser(int $userId): bool
    {
        return self::forget("user_{$userId}");
    }

    /**
     * Cache settings
     */
    public static function cacheSetting(string $key, $value, int $ttl = 86400): bool
    {
        return self::put("setting_{$key}", $value, $ttl);
    }

    /**
     * Get cached setting
     */
    public static function getCachedSetting(string $key, $default = null)
    {
        return self::get("setting_{$key}", $default);
    }

    /**
     * Cache product list
     */
    public static function cacheProducts(array $products, int $ttl = 1800): bool
    {
        return self::put('products_list', $products, $ttl);
    }

    /**
     * Get cached products
     */
    public static function getCachedProducts()
    {
        return self::get('products_list');
    }

    /**
     * Cache categories
     */
    public static function cacheCategories(array $categories, int $ttl = 3600): bool
    {
        return self::put('categories_list', $categories, $ttl);
    }

    /**
     * Get cached categories
     */
    public static function getCachedCategories()
    {
        return self::get('categories_list');
    }

    /**
     * Lock mechanism for preventing race conditions
     */
    public static function lock(string $key, int $seconds = 10): bool
    {
        return Cache::add(self::getKey("lock_{$key}"), true, $seconds);
    }

    /**
     * Release lock
     */
    public static function unlock(string $key): bool
    {
        return self::forget("lock_{$key}");
    }

    /**
     * Execute callback with lock
     */
    public static function withLock(string $key, callable $callback, int $seconds = 10)
    {
        if (!self::lock($key, $seconds)) {
            return false;
        }

        try {
            return $callback();
        } finally {
            self::unlock($key);
        }
    }
}
