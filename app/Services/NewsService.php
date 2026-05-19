<?php

namespace App\Services;

use App\Models\News;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class NewsService
{
    public function __construct(protected News $news) {}

    /**
     * Get published news (with caching per page)
     */
    public function getPublishedNews(int $perPage = 9): ServiceResult
    {
        try {
            $page = request()->get('page', 1);
            $cacheKey = "news:published:page:{$page}";

            $news = Cache::remember($cacheKey, 600, function () use ($perPage) {
                return $this->news::where('published', 1)
                    ->latest()
                    ->paginate($perPage);
            });

            return ServiceResult::success($news);
        } catch (\Exception $e) {
            Log::error('NewsService::getPublishedNews error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy danh sách tin tức', null, $e);
        }
    }

    /**
     * Get news by slug and increment view count (NO CACHING - increments view count)
     */
    public function getNewsBySlug(string $slug): ServiceResult
    {
        try {
            $news = $this->news::where('slug', $slug)
                ->where('published', 1)
                ->first();

            if (!$news) {
                return ServiceResult::error('Tin tức không tồn tại');
            }

            // Increment view count
            $news->increment('view_count');

            return ServiceResult::success($news);
        } catch (\Exception $e) {
            Log::error('NewsService::getNewsBySlug error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy thông tin tin tức', null, $e);
        }
    }

    /**
     * Get related news (with caching)
     */
    public function getRelatedNews(int $newsId, int $limit = 3): ServiceResult
    {
        try {
            $cacheKey = "news:related:{$newsId}";

            $relatedNews = Cache::remember($cacheKey, 900, function () use ($newsId, $limit) {
                return $this->news::where('published', 1)
                    ->where('id', '!=', $newsId)
                    ->latest()
                    ->take($limit)
                    ->get();
            });

            return ServiceResult::success($relatedNews);
        } catch (\Exception $e) {
            Log::error('NewsService::getRelatedNews error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy tin tức liên quan', null, $e);
        }
    }

    /**
     * Get latest news (with caching)
     */
    public function getLatestNews(int $limit = 8): ServiceResult
    {
        try {
            $cacheKey = "news:latest:{$limit}";

            $latestNews = Cache::remember($cacheKey, 900, function () use ($limit) {
                return $this->news::where('published', 1)
                    ->latest()
                    ->take($limit)
                    ->get();
            });

            return ServiceResult::success($latestNews);
        } catch (\Exception $e) {
            Log::error('NewsService::getLatestNews error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy tin tức mới nhất', null, $e);
        }
    }
}
