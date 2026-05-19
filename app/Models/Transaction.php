<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    const UPDATED_AT = null; // Only created_at

    // Transaction types
    const TYPE_SCRATCH_CARD = 0;
    const TYPE_BANK = 1;

    // Service types
    const SERVICE_TOPUP = 0;
    const SERVICE_BUY_ACCOUNT = 1;

    // Status
    const STATUS_PENDING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = 2;

    public static function labelStatus($status): string
    {
        switch ($status) {
            case self::STATUS_PENDING:
                return 'Chờ xử lý';
            case self::STATUS_SUCCESS:
                return 'Thành công';
            case self::STATUS_FAILED:
                return 'Thất bại';
            default:
                return 'Không xác định';
        }
    }

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'service_type',
        'status',
        'request_id',
        'provider',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'type' => 'integer',
            'service_type' => 'integer',
            'status' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function markAsSuccess(): void
    {
        $this->update(['status' => self::STATUS_SUCCESS]);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => self::STATUS_FAILED]);
    }
}
