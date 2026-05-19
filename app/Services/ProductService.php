<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function __construct(protected Product $product) {}

    /**
     * Get products with filters
     */
    public function getProducts(array $filters = []): ServiceResult
    {
        try {
            $query = $this->product::where('status', Product::STATUS_UNSOLD)
                ->with('category');

            // Filter by category (support parent-child hierarchy)
            if (!empty($filters['category'])) {
                $category = Category::find($filters['category']);

                if ($category) {
                    // If it's a parent category, include all child categories
                    if ($category->isParent() && $category->hasChildren()) {
                        $categoryIds = $category->children()->pluck('id')->push($category->id);
                        $query->whereIn('category_id', $categoryIds);
                    } else {
                        $query->where('category_id', $filters['category']);
                    }
                }
            }

            // Filter by price range
            if (!empty($filters['min_price'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('sale_price', '>=', $filters['min_price'])
                        ->orWhere(function ($q2) use ($filters) {
                            $q2->whereNull('sale_price')
                                ->where('sell_price', '>=', $filters['min_price']);
                        });
                });
            }

            if (!empty($filters['max_price'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('sale_price', '<=', $filters['max_price'])
                        ->orWhere(function ($q2) use ($filters) {
                            $q2->whereNull('sale_price')
                                ->where('sell_price', '<=', $filters['max_price']);
                        });
                });
            }

            // Sorting
            $sort = $filters['sort'] ?? 'newest';
            switch ($sort) {
                case 'price_low':
                    $query->orderByRaw('COALESCE(sale_price, sell_price) ASC');
                    break;
                case 'price_high':
                    $query->orderByRaw('COALESCE(sale_price, sell_price) DESC');
                    break;
                case 'discount':
                    $query->whereNotNull('sale_price')
                        ->where('sell_price', '>', 0)
                        ->orderByRaw('((sell_price - sale_price) / sell_price) DESC');
                    break;
                default:
                    $query->latest();
            }

            $perPage = $filters['per_page'] ?? 12;
            $products = $query->paginate($perPage);

            return ServiceResult::success($products);
        } catch (\Exception $e) {
            Log::error('ProductService::getProducts error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy danh sách sản phẩm', null, $e);
        }
    }

    /**
     * Get product by slug (with caching)
     */
    public function getProductBySlug(string $slug, bool $withCategory = true): ServiceResult
    {
        try {
            $cacheKey = "product:slug:{$slug}";

            $product = Cache::remember($cacheKey, 1800, function () use ($slug, $withCategory) {
                $query = $this->product::where('slug', $slug);

                if ($withCategory) {
                    $query->with('category');
                }

                return $query->first();
            });

            if (!$product) {
                return ServiceResult::error('Sản phẩm không tồn tại');
            }

            return ServiceResult::success($product);
        } catch (\Exception $e) {
            Log::error('ProductService::getProductBySlug error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy thông tin sản phẩm', null, $e);
        }
    }

    /**
     * Get related products (with caching)
     */
    public function getRelatedProducts(int $productId, int $categoryId, int $limit = 4): ServiceResult
    {
        try {
            $cacheKey = "products:related:{$productId}";

            $relatedProducts = Cache::remember($cacheKey, 900, function () use ($productId, $categoryId, $limit) {
                return $this->product::where('category_id', $categoryId)
                    ->where('id', '!=', $productId)
                    ->where('status', Product::STATUS_UNSOLD)
                    ->latest()
                    ->take($limit)
                    ->get();
            });

            return ServiceResult::success($relatedProducts);
        } catch (\Exception $e) {
            Log::error('ProductService::getRelatedProducts error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy sản phẩm liên quan', null, $e);
        }
    }

    /**
     * Get flash sale products (highest discount) - with caching
     */
    public function getFlashSaleProducts(int $limit = 16): ServiceResult
    {
        try {
            $cacheKey = 'products:flash_sale';

            $flashSaleProducts = Cache::remember($cacheKey, 600, function () use ($limit) {
                return $this->product::where('status', Product::STATUS_UNSOLD)
                    ->whereNotNull('sale_price')
                    ->whereNotNull('sell_price')
                    ->with('category')
                    ->get()
                    ->sortByDesc(function ($product) {
                        return $product->getDiscountPercent();
                    })
                    ->take($limit);
            });

            return ServiceResult::success($flashSaleProducts);
        } catch (\Exception $e) {
            Log::error('ProductService::getFlashSaleProducts error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy sản phẩm flash sale', null, $e);
        }
    }
}
