<?php

namespace App\Models;

use App\Constants\CardField;
use App\Constants\CardType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    const UPDATED_AT = null;

    // Status
    const STATUS_UNSOLD = 0;
    const STATUS_SOLD   = 1;

    public static function labelStatus(int $status): string
    {
        return match ($status) {
            self::STATUS_UNSOLD => 'Unsold',
            self::STATUS_SOLD   => 'Sold',
            default             => 'Unknown',
        };
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
        'status',
        'quantity',
        // Legacy generic columns (kept for backward compat, data migrated)
        'phone',
        'password',
        'email',
        'password2',
        'username',
        // Proper TCG columns
        CardField::CONDITION->value,
        CardField::LANGUAGE->value,
        CardField::SET->value,
        CardField::RARITY->value,
        CardField::GRADING->value,
        CardField::GRADE->value,
        CardField::CERT->value,
        CardField::TYPE->value,
    ];

    protected function casts(): array
    {
        return [
            'sell_price'                => 'decimal:2',
            'sale_price'                => 'decimal:2',
            'status'                    => 'integer',
            'quantity'                  => 'integer',
            'images'                    => 'array',
            'created_at'                => 'datetime',
            CardField::TYPE->value      => 'integer',
        ];
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ── Accessors using CardField enum ───────────────────────────────────────

    public function getConditionAttribute(): ?string
    {
        return $this->attributes[CardField::CONDITION->value] ?? null;
    }

    public function getLanguageAttribute(): ?string
    {
        return $this->attributes[CardField::LANGUAGE->value] ?? null;
    }

    public function getSetAttribute(): ?string
    {
        return $this->attributes[CardField::SET->value] ?? null;
    }

    public function getRarityAttribute(): ?string
    {
        return $this->attributes[CardField::RARITY->value] ?? null;
    }

    public function getGradingAttribute(): ?string
    {
        return $this->attributes[CardField::GRADING->value] ?? null;
    }

    public function getGradeAttribute(): ?string
    {
        return $this->attributes[CardField::GRADE->value] ?? null;
    }

    public function getCertAttribute(): ?string
    {
        return $this->attributes[CardField::CERT->value] ?? null;
    }

    public function getCardTypeAttribute(): int
    {
        return (int) ($this->attributes[CardField::TYPE->value] ?? CardType::SINGLE->value);
    }

    public function isSingleCard(): bool
    {
        return $this->card_type === CardType::SINGLE->value;
    }

    public function isGraded(): bool
    {
        return !empty($this->grading) && $this->grading !== 'RAW';
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isSold(): bool
    {
        return $this->status === self::STATUS_SOLD || $this->quantity <= 0;
    }

    public function isUnsold(): bool
    {
        return $this->status === self::STATUS_UNSOLD && $this->quantity > 0;
    }

    public function markAsSold(): void
    {
        $this->update([
            'status' => self::STATUS_SOLD,
            'quantity' => 0,
        ]);
    }

    public function hasStock(int $amount = 1): bool
    {
        return $this->quantity >= $amount;
    }

    public function decrementStock(int $amount = 1): void
    {
        $newQuantity = max(0, $this->quantity - $amount);

        $this->update([
            'quantity' => $newQuantity,
            'status' => $newQuantity > 0 ? self::STATUS_UNSOLD : self::STATUS_SOLD,
        ]);
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
        return (float) ($this->sale_price ?? $this->sell_price ?? 0);
    }
}
