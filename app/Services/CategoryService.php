<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    public function __construct(protected Category $category) {}

    /**
     * Get category by slug (with caching)
     */
    public function getCategoryBySlug(string $slug): ServiceResult
    {
        try {
            $cacheKey = "category:slug:{$slug}";

            $category = Cache::remember($cacheKey, 3600, function () use ($slug) {
                return $this->category::where('slug', $slug)->first();
            });

            if (!$category) {
                return ServiceResult::error('Danh mục không tồn tại');
            }

            return ServiceResult::success($category);
        } catch (\Exception $e) {
            Log::error('CategoryService::getCategoryBySlug error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy thông tin danh mục', null, $e);
        }
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory(int $categoryId, int $perPage = 12): ServiceResult
    {
        try {
            $products = Product::where('category_id', $categoryId)
                ->where('status', Product::STATUS_UNSOLD)
                ->with('category')
                ->latest()
                ->paginate($perPage);

            return ServiceResult::success($products);
        } catch (\Exception $e) {
            Log::error('CategoryService::getProductsByCategory error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy sản phẩm của danh mục', null, $e);
        }
    }

    /**
     * Get featured parent categories with children (with caching)
     */
    public function getFeaturedCategories(int $limit = 4): ServiceResult
    {
        try {
            $cacheKey = 'categories:featured:' . $limit;

            $categories = Cache::remember($cacheKey, 3600, function () use ($limit) {
                return $this->category::whereNull('parent_id')
                    ->with('children')
                    ->latest()
                    ->take($limit)
                    ->get();
            });

            return ServiceResult::success($categories);
        } catch (\Exception $e) {
            Log::error('CategoryService::getFeaturedCategories error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy danh mục nổi bật', null, $e);
        }
    }

    /**
     * Get all categories with parent-child relationships (with caching)
     */
    public function getAllCategoriesWithChildren(): ServiceResult
    {
        try {
            $cacheKey = 'categories:all_with_children';

            $categories = Cache::remember($cacheKey, 3600, function () {
                return $this->category::whereNull('parent_id')
                    ->with('children')
                    ->orderBy('title')
                    ->get();
            });

            return ServiceResult::success($categories);
        } catch (\Exception $e) {
            Log::error('CategoryService::getAllCategoriesWithChildren error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy danh sách danh mục', null, $e);
        }
    }
}
