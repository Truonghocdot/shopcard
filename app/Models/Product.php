<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    const UPDATED_AT = null; // Only created_at

    // Product types
    const TYPE_ACCOUNT = 1;
    const TYPE_EXTRA = 2;

    // Status
    const STATUS_UNSOLD = 0;
    const STATUS_SOLD = 1;

    public static function labelStatus($status): string
    {
        switch ($status) {
            case self::STATUS_UNSOLD:
                return 'Chưa bán';
            case self::STATUS_SOLD:
                return 'Đã bán';
            default:
                return 'Không xác định';
        }
    }

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'sell_price',
        'sale_price',
        'meta_title',
        'meta_description',
        'images',
        'type',
        'status',
        'phone',
        'password',
        'email',
        'password2',
        'username'
    ];

    protected function casts(): array
    {
        return [
            'sell_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'type' => 'integer',
            'status' => 'integer',
            'images' => 'array',
            'created_at' => 'datetime',
            'phone' => 'string',
            'password' => 'string',
            'email' => 'string',
            'password2' => 'string',
            'username' => 'string',
        ];
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Helper methods
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isSold(): bool
    {
        return $this->status === self::STATUS_SOLD;
    }

    public function isUnsold(): bool
    {
        return $this->status === self::STATUS_UNSOLD;
    }

    public function markAsSold(): void
    {
        $this->update(['status' => self::STATUS_SOLD]);
    }

    public function getDiscountPercent(): ?float
    {
        if (!$this->sale_price || !$this->sell_price) {
            return null;
        }
        return round((($this->sell_price - $this->sale_price) / $this->sell_price) * 100, 2);
    }

    public function getFinalPrice(): float
    {
        return $this->sale_price ?? $this->sell_price ?? 0;
    }
}
