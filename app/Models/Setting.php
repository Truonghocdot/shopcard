<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'setting_name',
        'setting_value',
    ];

    // Helper methods
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('setting_name', $key)->first();
            return $setting?->setting_value ?? $default;
        });
    }

    public static function set(string $key, $value): void
    {
        self::updateOrCreate(
            ['setting_name' => $key],
            ['setting_value' => $value]
        );
        Cache::forget("setting_{$key}");
    }

    public static function has(string $key): bool
    {
        return self::where('setting_name', $key)->exists();
    }

    public static function forget(string $key): void
    {
        self::where('setting_name', $key)->delete();
        Cache::forget("setting_{$key}");
    }

    public static function getAllSettings(): array
    {
        return Cache::remember('settings_all', 3600, function () {
            return self::pluck('setting_value', 'setting_name')->toArray();
        });
    }

    public static function clearCache(): void
    {
        Cache::flush();
    }
}
