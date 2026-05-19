<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class News extends Model implements Feedable
{
    // Published status
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'thumbnail',
        'published',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'integer',
            'view_count' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary($this->description)
            ->updated($this->updated_at)
            ->link(route('news.show', $this))
            ->authorName('VanhFCO') // Change this to your name
            ->authorEmail('truonghocdot05@gmail.com'); // Change this to your email
    }

    public static function getFeedItems()
    {
        return self::published()->latest()->limit(20)->get();
    }


    // Helper methods
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isPublished(): bool
    {
        return $this->published === self::STATUS_PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this->published === self::STATUS_DRAFT;
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function publish(): void
    {
        $this->update(['published' => self::STATUS_PUBLISHED]);
    }

    public function unpublish(): void
    {
        $this->update(['published' => self::STATUS_DRAFT]);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('published', self::STATUS_PUBLISHED);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
