<?php

/**
 * Global Helper Functions
 * 
 * This file provides convenient global helper functions
 * that wrap the utility classes for easier usage throughout the application.
 */

use App\Utilts\Helper;
use App\Utilts\Caching;
use App\Utilts\Logger;

if (!function_exists('format_currency')) {
    function format_currency(float $amount, bool $showSymbol = true): string
    {
        return Helper::formatCurrency($amount, $showSymbol);
    }
}

if (!function_exists('format_number')) {
    function format_number(int $number): string
    {
        return Helper::formatNumber($number);
    }
}

if (!function_exists('generate_code')) {
    function generate_code(int $length = 8, string $prefix = ''): string
    {
        return Helper::generateCode($length, $prefix);
    }
}

if (!function_exists('generate_slug')) {
    function generate_slug(string $text): string
    {
        return Helper::generateSlug($text);
    }
}

if (!function_exists('mask_phone')) {
    function mask_phone(string $phone): string
    {
        return Helper::maskPhone($phone);
    }
}

if (!function_exists('mask_email')) {
    function mask_email(string $email): string
    {
        return Helper::maskEmail($email);
    }
}

if (!function_exists('is_valid_phone')) {
    function is_valid_phone(string $phone): bool
    {
        return Helper::isValidPhone($phone);
    }
}

if (!function_exists('time_ago')) {
    function time_ago($datetime): string
    {
        return Helper::timeAgo($datetime);
    }
}

if (!function_exists('cache_get')) {
    function cache_get(string $key, $default = null)
    {
        return Caching::get($key, $default);
    }
}

if (!function_exists('cache_put')) {
    function cache_put(string $key, $value, ?int $ttl = null): bool
    {
        return Caching::put($key, $value, $ttl);
    }
}

if (!function_exists('cache_remember')) {
    function cache_remember(string $key, callable $callback, ?int $ttl = null)
    {
        return Caching::remember($key, $callback, $ttl);
    }
}

if (!function_exists('log_activity')) {
    function log_activity(int $userId, string $action, array $data = []): void
    {
        Logger::userActivity($userId, $action, $data);
    }
}

if (!function_exists('log_transaction')) {
    function log_transaction(int $transactionId, string $status, array $data = []): void
    {
        Logger::transaction($transactionId, $status, $data);
    }
}

if (!function_exists('log_exception')) {
    function log_exception(Throwable $exception, array $context = []): void
    {
        Logger::exception($exception, $context);
    }
}
