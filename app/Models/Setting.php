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

    public static function getLocalized(string $key, ?string $locale = null, $default = null): mixed
    {
        $locale ??= app()->getLocale();
        $fallback = config('app.fallback_locale', config('locales.default', 'en'));
        $value = self::get($key, $default);

        if (! is_string($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            return $value;
        }

        return $decoded[$locale]
            ?? $decoded[$fallback]
            ?? collect($decoded)->filter(fn ($item) => filled($item))->first()
            ?? $default;
    }

    public static function setLocalized(string $key, array $value): void
    {
        self::set($key, json_encode($value, JSON_UNESCAPED_UNICODE));
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
