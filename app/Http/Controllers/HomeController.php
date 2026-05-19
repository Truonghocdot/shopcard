<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\NewsService;
use App\Services\LeaderboardService;
use Illuminate\Support\Facades\Cache;
use App\Models\Banner;

class HomeController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected ProductService $productService,
        protected NewsService $newsService,
        protected LeaderboardService $leaderboardService
    ) {}

    public function index()
    {
        $banners = Cache::remember('home_banners', 3600, function () {
            return Banner::orderBy('sort', 'asc')->get();
        });

        // Get featured parent categories (no longer limited to 4)
        $categoriesResult = $this->categoryService->getFeaturedCategories(20);
        $categories = $categoriesResult->isSuccess() ? $categoriesResult->getData() : collect();

        // Get 16 flash sale products (highest discount)
        $flashSaleProductsResult = $this->productService->getFlashSaleProducts(16);
        $flashSaleProducts = $flashSaleProductsResult->isSuccess() ? $flashSaleProductsResult->getData() : collect();

        // Get 8 latest news
        $latestNewsResult = $this->newsService->getLatestNews(8);
        $latestNews = $latestNewsResult->isSuccess() ? $latestNewsResult->getData() : collect();

        // Get top 11 spenders for leaderboard
        $topSpenders = $this->leaderboardService->getTopSpenders(11);

        return view('home', compact('categories', 'flashSaleProducts', 'latestNews', 'topSpenders', 'banners'));
    }
}
