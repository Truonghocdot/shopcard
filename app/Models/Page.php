<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Page extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const SLUG_ABOUT_US = 'about-us';
    const SLUG_CONTACT = 'contact';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'status',
        'show_in_header',
        'show_in_footer',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'content' => 'array',
            'meta_title' => 'array',
            'meta_description' => 'array',
            'status' => 'integer',
            'show_in_header' => 'boolean',
            'show_in_footer' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeForHeader(Builder $query): Builder
    {
        return $query->active()
            ->where('show_in_header', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function scopeForFooter(Builder $query): Builder
    {
        return $query->active()
            ->where('show_in_footer', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public static function locales(): array
    {
        return array_keys(config('locales.supported', ['en' => 'English']));
    }

    public function getTranslation(string $field, ?string $locale = null, ?string $fallback = null): ?string
    {
        $locale ??= app()->getLocale();
        $fallback ??= config('app.fallback_locale', config('locales.default', 'en'));

        $value = $this->getAttributeValue($field);

        if (is_string($value)) {
            return $value;
        }

        if (! is_array($value)) {
            return null;
        }

        return $value[$locale]
            ?? $value[$fallback]
            ?? collect($value)->filter(fn ($item) => filled($item))->first();
    }

    public function getTitleAttribute($value): ?string
    {
        return $this->getTranslationValue('title', $value);
    }

    public function getContentAttribute($value): ?string
    {
        return $this->getTranslationValue('content', $value);
    }

    public function getMetaTitleAttribute($value): ?string
    {
        return $this->getTranslationValue('meta_title', $value);
    }

    public function getMetaDescriptionAttribute($value): ?string
    {
        return $this->getTranslationValue('meta_description', $value);
    }

    public function getRawTranslation(string $field): array
    {
        $value = $this->getRawOriginal($field);

        if (blank($value)) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return [config('locales.default', 'en') => $value];
    }

    private function getTranslationValue(string $field, mixed $value): ?string
    {
        if (is_array($value)) {
            return $this->getTranslation($field);
        }

        if (blank($value)) {
            return null;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded[app()->getLocale()]
                ?? $decoded[config('app.fallback_locale', 'en')]
                ?? collect($decoded)->filter(fn ($item) => filled($item))->first();
        }

        return $value;
    }
}
