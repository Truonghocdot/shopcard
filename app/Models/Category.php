<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    const UPDATED_AT = null; // Only created_at

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'description',
        'image',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Parent-child relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('title');
    }

    // Helper methods
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isParent(): bool
    {
        return $this->parent_id === null;
    }

    public function isChild(): bool
    {
        return $this->parent_id !== null;
    }

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }
}
