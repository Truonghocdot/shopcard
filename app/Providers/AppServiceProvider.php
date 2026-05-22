<?php

namespace App\Providers;

use App\Constants\SettingName;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        app()->setLocale(config('locales.default', config('app.locale')));

        // Register observers
        \App\Models\Setting::observe(\App\Observers\SettingObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
        \App\Models\News::observe(\App\Observers\NewsObserver::class);
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        \App\Models\Banner::observe(\App\Observers\BannerObserver::class);

        View::composer(['components.header', 'components.footer', 'layouts.app'], function ($view): void {
            $headerPages = Page::forHeader()->get();
            $footerPages = Page::forFooter()->get();

            $view->with('headerPages', $headerPages)
                ->with('footerPages', $footerPages)
                ->with('siteSettings', [
                    'site_name' => Setting::getLocalized(SettingName::SITE_NAME->value, default: 'Rabby TCG'),
                    'site_tagline' => Setting::getLocalized(SettingName::SITE_TAGLINE->value, default: __('premium_tcg_shop')),
                    'site_description' => Setting::getLocalized(SettingName::SITE_DESCRIPTION->value, default: 'Rabby TCG is the premier store for authentic graded trading card games, Pokemon cards, and premium collectibles.'),
                    'footer_about' => Setting::getLocalized(SettingName::FOOTER_ABOUT->value, default: __('footer_about_desc')),
                    'footer_copyright' => Setting::getLocalized(SettingName::FOOTER_COPYRIGHT->value, default: __('copyright_text')),
                    'contact_email' => Setting::get(SettingName::SITE_CONTACT_EMAIL->value, 'support@rabbytcg.com'),
                    'contact_phone' => Setting::get(SettingName::SITE_CONTACT_PHONE->value, Setting::get(SettingName::PHONE_NUMBER->value, '')),
                    'support_hours' => Setting::getLocalized(SettingName::SUPPORT_HOURS->value, default: '08:00AM - 22:00PM'),
                    'facebook_link' => Setting::get(SettingName::FACEBOOK_LINK->value),
                    'instagram_link' => Setting::get(SettingName::INSTAGRAM_LINK->value),
                    'tiktok_link' => Setting::get(SettingName::TIKTOK_LINK->value),
                    'zalo_link' => Setting::get(SettingName::ZALO_LINK->value),
                ]);
        });
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
