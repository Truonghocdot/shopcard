<?php

namespace App\Providers;

use App\Models\Setting;
use App\Observers\SettingObserver;
use App\Services\AuthService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerService();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        \App\Models\Setting::observe(\App\Observers\SettingObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
        \App\Models\News::observe(\App\Observers\NewsObserver::class);
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        \App\Models\Banner::observe(\App\Observers\BannerObserver::class);
    }


    // Register all services

    private function registerService()
    {
        $this->app->bind(\App\Services\AuthService::class);
        $this->app->bind(\App\Services\ProductService::class);
        $this->app->bind(\App\Services\CategoryService::class);
        $this->app->bind(\App\Services\NewsService::class);
        $this->app->bind(\App\Services\CouponService::class);
        $this->app->bind(\App\Services\WalletService::class);
        $this->app->bind(\App\Services\OrderService::class);
        $this->app->bind(\App\Services\UserService::class);
        $this->app->bind(\App\Services\TransactionService::class);
        $this->app->bind(\App\Services\DepositService::class);
        $this->app->bind(\App\Services\WebhookService::class);
        $this->app->bind(\App\Services\LeaderboardService::class);
        $this->app->bind(\App\Services\ViewDataService::class);
        $this->app->singleton(\App\Services\CacheService::class);
    }
}
