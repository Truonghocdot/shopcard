<?php

namespace App\Utilts;

use Illuminate\Support\Str;

class Helper
{
    /**
     * Format currency in VND
     */
    public static function formatCurrency(float $amount, bool $showSymbol = true): string
    {
        $formatted = number_format($amount, 0, ',', '.');
        return $showSymbol ? $formatted . 'đ' : $formatted;
    }

    /**
     * Format number with K, M, B suffixes
     */
    public static function formatNumber(int $number): string
    {
        if ($number >= 1000000000) {
            return round($number / 1000000000, 1) . 'B';
        }
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }
        if ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return (string) $number;
    }

    /**
     * Generate unique code
     */
    public static function generateCode(int $length = 8, string $prefix = ''): string
    {
        $code = strtoupper(Str::random($length));
        return $prefix ? $prefix . '-' . $code : $code;
    }

    /**
     * Generate slug from string
     */
    public static function generateSlug(string $text): string
    {
        // Convert Vietnamese characters
        $text = self::removeVietnameseTones($text);
        return Str::slug($text);
    }

    /**
     * Remove Vietnamese tones
     */
    public static function removeVietnameseTones(string $str): string
    {
        $vietnameseTones = [
            'à',
            'á',
            'ạ',
            'ả',
            'ã',
            'â',
            'ầ',
            'ấ',
            'ậ',
            'ẩ',
            'ẫ',
            'ă',
            'ằ',
            'ắ',
            'ặ',
            'ẳ',
            'ẵ',
            'è',
            'é',
            'ẹ',
            'ẻ',
            'ẽ',
            'ê',
            'ề',
            'ế',
            'ệ',
            'ể',
            'ễ',
            'ì',
            'í',
            'ị',
            'ỉ',
            'ĩ',
            'ò',
            'ó',
            'ọ',
            'ỏ',
            'õ',
            'ô',
            'ồ',
            'ố',
            'ộ',
            'ổ',
            'ỗ',
            'ơ',
            'ờ',
            'ớ',
            'ợ',
            'ở',
            'ỡ',
            'ù',
            'ú',
            'ụ',
            'ủ',
            'ũ',
            'ư',
            'ừ',
            'ứ',
            'ự',
            'ử',
            'ữ',
            'ỳ',
            'ý',
            'ỵ',
            'ỷ',
            'ỹ',
            'đ',
            'À',
            'Á',
            'Ạ',
            'Ả',
            'Ã',
            'Â',
            'Ầ',
            'Ấ',
            'Ậ',
            'Ẩ',
            'Ẫ',
            'Ă',
            'Ằ',
            'Ắ',
            'Ặ',
            'Ẳ',
            'Ẵ',
            'È',
            'É',
            'Ẹ',
            'Ẻ',
            'Ẽ',
            'Ê',
            'Ề',
            'Ế',
            'Ệ',
            'Ể',
            'Ễ',
            'Ì',
            'Í',
            'Ị',
            'Ỉ',
            'Ĩ',
            'Ò',
            'Ó',
            'Ọ',
            'Ỏ',
            'Õ',
            'Ô',
            'Ồ',
            'Ố',
            'Ộ',
            'Ổ',
            'Ỗ',
            'Ơ',
            'Ờ',
            'Ớ',
            'Ợ',
            'Ở',
            'Ỡ',
            'Ù',
            'Ú',
            'Ụ',
            'Ủ',
            'Ũ',
            'Ư',
            'Ừ',
            'Ứ',
            'Ự',
            'Ử',
            'Ữ',
            'Ỳ',
            'Ý',
            'Ỵ',
            'Ỷ',
            'Ỹ',
            'Đ'
        ];

        $replacements = [
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'i',
            'i',
            'i',
            'i',
            'i',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'y',
            'y',
            'y',
            'y',
            'y',
            'd',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'I',
            'I',
            'I',
            'I',
            'I',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'Y',
            'Y',
            'Y',
            'Y',
            'Y',
            'D'
        ];

        return str_replace($vietnameseTones, $replacements, $str);
    }

    /**
     * Mask sensitive data (phone, email)
     */
    public static function maskPhone(string $phone): string
    {
        if (strlen($phone) < 4) {
            return $phone;
        }
        return substr($phone, 0, 3) . str_repeat('*', strlen($phone) - 6) . substr($phone, -3);
    }

    public static function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }
        $name = $parts[0];
        $domain = $parts[1];
        $nameLength = strlen($name);
        if ($nameLength <= 2) {
            return $email;
        }
        $maskedName = substr($name, 0, 2) . str_repeat('*', $nameLength - 2);
        return $maskedName . '@' . $domain;
    }

    /**
     * Validate Vietnamese phone number
     */
    public static function isValidPhone(string $phone): bool
    {
        // Remove spaces and dashes
        $phone = preg_replace('/[\s\-]/', '', $phone);

        // Check if it's a valid Vietnamese phone number (10 digits starting with 0)
        return preg_match('/^0[0-9]{9}$/', $phone) === 1;
    }

    /**
     * Format date time to Vietnamese format
     */
    public static function formatDateTime($datetime, string $format = 'd/m/Y H:i'): string
    {
        if (!$datetime) {
            return '';
        }
        return $datetime->format($format);
    }

    /**
     * Get time ago in Vietnamese
     */
    public static function timeAgo($datetime): string
    {
        if (!$datetime) {
            return '';
        }

        $now = now();
        $diff = $datetime->diff($now);

        if ($diff->y > 0) {
            return $diff->y . ' năm trước';
        }
        if ($diff->m > 0) {
            return $diff->m . ' tháng trước';
        }
        if ($diff->d > 0) {
            return $diff->d . ' ngày trước';
        }
        if ($diff->h > 0) {
            return $diff->h . ' giờ trước';
        }
        if ($diff->i > 0) {
            return $diff->i . ' phút trước';
        }
        return 'Vừa xong';
    }

    /**
     * Generate random string
     */
    public static function randomString(int $length = 10): string
    {
        return Str::random($length);
    }

    /**
     * Truncate text
     */
    public static function truncate(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . $suffix;
    }

    /**
     * Get file size in human readable format
     */
    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        return number_format($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }

    /**
     * Check if string is JSON
     */
    public static function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Get client IP address
     */
    public static function getClientIp(): string
    {
        return request()->ip();
    }

    /**
     * Get user agent
     */
    public static function getUserAgent(): string
    {
        return request()->userAgent() ?? '';
    }
}
