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
            Stat::make('Tổng doanh thu', Number::currency($revenue, 'VND'))
                ->description('Tổng tiền giao dịch thành công')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Thu nhập tháng này', Number::currency($monthlyRevenue, 'VND'))
                ->description('Doanh thu trong tháng ' . now()->month)
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),
            Stat::make('Tổng người dùng', $totalUsers)
                ->description('Tổng số khách hàng hệ thống')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            Stat::make('Người dùng mới (Tháng này)', $newUsers)
                ->description('Khách hàng mới đăng ký')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Giao dịch chờ', $pendingTransactions)
                ->description('Cần xử lý ngay')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color($pendingTransactions > 0 ? 'warning' : 'gray'),
            Stat::make('Sản phẩm đã bán', $soldProducts)
                ->description('Tổng số acc đã bán ra')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),

            // New Stats Widgets
            Stat::make('Sản phẩm tồn kho', $stockCount)
                ->description('Tổng số acc chưa bán')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('warning'),
            Stat::make('Giá trị tồn kho', Number::currency($stockValue, 'VND'))
                ->description('Tổng giá trị acc chưa bán')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
            Stat::make('Tiền chi Coupon', Number::currency($couponSpend, 'VND'))
                ->description('Tổng tiền giảm giá từ coupon')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('danger'),
        ];
    }
}
