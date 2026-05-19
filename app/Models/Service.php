<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    const UPDATED_AT = null; // Only created_at

    // Status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'meta_title',
        'meta_description',
        'used_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'used_count' => 'integer',
            'status' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    // Helper methods
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function incrementUsedCount(): void
    {
        $this->increment('used_count');
    }

    public function activate(): void
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
    }

    public function deactivate(): void
    {
        $this->update(['status' => self::STATUS_INACTIVE]);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('used_count', 'desc');
    }
}
