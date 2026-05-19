<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;

class Coupon extends Model
{
    // Discount types
    const DISCOUNT_PERCENTAGE = 1;
    const DISCOUNT_FIXED = 2;

    // Status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_order_amount',
        'usage_limit',
        'usage_count',
        'usage_per_user',
        'start_date',
        'end_date',
        'status',
        'excluded_min_price',
        'excluded_max_price',
    ];

    protected function casts(): array
    {
        return [
            'discount_type' => 'integer',
            'discount_value' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_count' => 'integer',
            'usage_per_user' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'status' => 'integer',
            'excluded_min_price' => 'decimal:2',
            'excluded_max_price' => 'decimal:2',
        ];
    }

    // Relationships
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function excludedCategories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'coupon_excluded_categories');
    }

    // Helper methods
    public function isActive(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $now = now();
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    public function isExpired(): bool
    {
        return $this->end_date && now()->gt($this->end_date);
    }

    public function hasReachedLimit(): bool
    {
        return $this->usage_limit && $this->usage_count >= $this->usage_limit;
    }

    /**
     * Check if product is excluded by category + price range
     * Coupon is rejected when product belongs to an excluded category
     * AND its price falls within the excluded price range.
     */
    public function isExcludedByCategoryAndPrice(Product $product): bool
    {
        // Both conditions must be set
        if (is_null($this->excluded_min_price) || is_null($this->excluded_max_price)) {
            return false;
        }

        $excludedCategoryIds = $this->excludedCategories()->pluck('categories.id')->toArray();
        if (empty($excludedCategoryIds)) {
            return false;
        }

        // Check: product in excluded category AND price in excluded range
        if (!in_array($product->category_id, $excludedCategoryIds)) {
            return false;
        }

        $productPrice = $product->getFinalPrice();
        return $productPrice >= (float)$this->excluded_min_price
            && $productPrice <= (float)$this->excluded_max_price;
    }

    public function canBeUsedBy(User $user, float $orderAmount, ?Product $product = null): array
    {
        if (!$this->isActive()) {
            return ['valid' => false, 'message' => 'Mã giảm giá không còn hiệu lực'];
        }

        if ($this->hasReachedLimit()) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng'];
        }

        if ($orderAmount < $this->min_order_amount) {
            return ['valid' => false, 'message' => "Đơn hàng tối thiểu " . number_format((float)$this->min_order_amount) . "đ"];
        }

        $userUsageCount = $this->usages()->where('user_id', $user->id)->count();
        if ($userUsageCount >= $this->usage_per_user) {
            return ['valid' => false, 'message' => 'Bạn đã sử dụng hết lượt cho mã này'];
        }

        // Check category + price range exclusion
        if ($product && $this->isExcludedByCategoryAndPrice($product)) {
            $categoryNames = $this->excludedCategories()->pluck('title')->implode(', ');
            return [
                'valid' => false,
                'message' => 'Mã giảm giá không áp dụng cho sản phẩm thuộc danh mục ['
                    . $categoryNames . '] có giá trong khoảng '
                    . number_format((float)$this->excluded_min_price) . 'đ - '
                    . number_format((float)$this->excluded_max_price) . 'đ'
            ];
        }

        return ['valid' => true, 'message' => 'Mã giảm giá hợp lệ'];
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if ($this->discount_type === self::DISCOUNT_PERCENTAGE) {
            $discount = ($orderAmount * $this->discount_value) / 100;
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            return $discount;
        }

        return min($this->discount_value, $orderAmount);
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }
}
