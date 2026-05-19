<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{

    protected function getStats(): array
    {
        $revenue = Transaction::where('status', 1)->sum('amount');
        $monthlyRevenue = Transaction::where('status', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        $totalUsers = User::where('role', 0)->count();
        $newUsers = User::where('role', 0)->whereMonth('created_at', now()->month)->count();
        $pendingTransactions = Transaction::where('status', 0)->count();
        $soldProducts = Product::where('status', 1)->count();

        // New Stats
        $stockCount = Product::where('status', Product::STATUS_UNSOLD)->count();
        $stockValue = Product::where('status', Product::STATUS_UNSOLD)
            ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(sale_price, sell_price)'));
        $couponSpend = \App\Models\Order::where('status', \App\Models\Order::STATUS_COMPLETED)->sum('discount_amount');

        return [
            Stat::make(__('filament.widget_total_revenue'), Number::currency($revenue, 'VND'))
                ->description(__('filament.widget_total_revenue_desc'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make(__('filament.widget_monthly_revenue'), Number::currency($monthlyRevenue, 'VND'))
                ->description(__('filament.widget_monthly_revenue_desc') . ' ' . now()->month)
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),
            Stat::make(__('filament.widget_total_users'), $totalUsers)
                ->description(__('filament.widget_total_users_desc'))
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            Stat::make(__('filament.widget_new_users'), $newUsers)
                ->description(__('filament.widget_new_users_desc'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make(__('filament.widget_pending_transactions'), $pendingTransactions)
                ->description(__('filament.widget_pending_transactions_desc'))
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color($pendingTransactions > 0 ? 'warning' : 'gray'),
            Stat::make(__('filament.widget_sold_products'), $soldProducts)
                ->description(__('filament.widget_sold_products_desc'))
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),

            // New Stats Widgets
            Stat::make(__('filament.widget_stock'), $stockCount)
                ->description(__('filament.widget_stock_desc'))
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('warning'),
            Stat::make(__('filament.widget_stock_value'), Number::currency($stockValue, 'VND'))
                ->description(__('filament.widget_stock_value_desc'))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
            Stat::make(__('filament.widget_coupon_spend'), Number::currency($couponSpend, 'VND'))
                ->description(__('filament.widget_coupon_spend_desc'))
                ->descriptionIcon('heroicon-m-ticket')
                ->color('danger'),
        ];
    }
}
