<?php

namespace App\Utilts;

use Illuminate\Support\Facades\Log;
use Throwable;

class Logger
{
    /**
     * Log info message
     */
    public static function info(string $message, array $context = []): void
    {
        Log::info($message, $context);
    }

    /**
     * Log error message
     */
    public static function error(string $message, array $context = []): void
    {
        Log::error($message, $context);
    }

    /**
     * Log warning message
     */
    public static function warning(string $message, array $context = []): void
    {
        Log::warning($message, $context);
    }

    /**
     * Log debug message
     */
    public static function debug(string $message, array $context = []): void
    {
        Log::debug($message, $context);
    }

    /**
     * Log exception
     */
    public static function exception(Throwable $exception, array $context = []): void
    {
        Log::error($exception->getMessage(), array_merge([
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ], $context));
    }

    /**
     * Log user activity
     */
    public static function userActivity(int $userId, string $action, array $data = []): void
    {
        self::info("User activity: {$action}", [
            'user_id' => $userId,
            'action' => $action,
            'data' => $data,
            'ip' => Helper::getClientIp(),
            'user_agent' => Helper::getUserAgent(),
        ]);
    }

    /**
     * Log transaction
     */
    public static function transaction(int $transactionId, string $status, array $data = []): void
    {
        self::info("Transaction {$status}", [
            'transaction_id' => $transactionId,
            'status' => $status,
            'data' => $data,
        ]);
    }

    /**
     * Log payment
     */
    public static function payment(string $provider, string $action, array $data = []): void
    {
        self::info("Payment {$action}", [
            'provider' => $provider,
            'action' => $action,
            'data' => $data,
        ]);
    }

    /**
     * Log API request
     */
    public static function apiRequest(string $method, string $url, array $data = []): void
    {
        self::info("API Request: {$method} {$url}", [
            'method' => $method,
            'url' => $url,
            'data' => $data,
            'ip' => Helper::getClientIp(),
        ]);
    }

    /**
     * Log API response
     */
    public static function apiResponse(string $url, int $statusCode, $response = null): void
    {
        self::info("API Response: {$url}", [
            'url' => $url,
            'status_code' => $statusCode,
            'response' => $response,
        ]);
    }

    /**
     * Log authentication event
     */
    public static function auth(string $event, int $userId = null, array $data = []): void
    {
        self::info("Auth: {$event}", [
            'event' => $event,
            'user_id' => $userId,
            'data' => $data,
            'ip' => Helper::getClientIp(),
        ]);
    }

    /**
     * Log database query (for debugging)
     */
    public static function query(string $sql, array $bindings = [], float $time = null): void
    {
        if (config('app.debug')) {
            self::debug("Database Query", [
                'sql' => $sql,
                'bindings' => $bindings,
                'time' => $time,
            ]);
        }
    }

    /**
     * Log security event
     */
    public static function security(string $event, array $data = []): void
    {
        self::warning("Security Event: {$event}", array_merge([
            'event' => $event,
            'ip' => Helper::getClientIp(),
            'user_agent' => Helper::getUserAgent(),
        ], $data));
    }

    /**
     * Log coupon usage
     */
    public static function couponUsage(string $couponCode, int $userId, float $discountAmount): void
    {
        self::info("Coupon used", [
            'coupon_code' => $couponCode,
            'user_id' => $userId,
            'discount_amount' => $discountAmount,
        ]);
    }

    /**
     * Log product purchase
     */
    public static function productPurchase(int $productId, int $userId, float $amount): void
    {
        self::info("Product purchased", [
            'product_id' => $productId,
            'user_id' => $userId,
            'amount' => $amount,
        ]);
    }

    /**
     * Log wallet transaction
     */
    public static function walletTransaction(int $userId, string $type, float $amount, float $balanceBefore, float $balanceAfter): void
    {
        self::info("Wallet transaction", [
            'user_id' => $userId,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
        ]);
    }

    /**
     * Log to custom channel
     */
    public static function channel(string $channel, string $level, string $message, array $context = []): void
    {
        Log::channel($channel)->log($level, $message, $context);
    }

    /**
     * Log critical error
     */
    public static function critical(string $message, array $context = []): void
    {
        Log::critical($message, $context);
    }

    /**
     * Log alert
     */
    public static function alert(string $message, array $context = []): void
    {
        Log::alert($message, $context);
    }

    /**
     * Log emergency
     */
    public static function emergency(string $message, array $context = []): void
    {
        Log::emergency($message, $context);
    }
}
