<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProductsSoldChart extends ChartWidget
{
    protected ?string $heading = 'Lượng sản phẩm bán được';
    protected ?string $maxHeight = '300px';

    public ?string $filter = 'month';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $query = Order::where('status', Order::STATUS_COMPLETED);

        switch ($activeFilter) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                $dbFormat = '%H:00';
                $interval = 'hour';
                break;
            case 'week':
                $start = now()->subDays(6)->startOfDay();
                $end = now()->endOfDay();
                $dbFormat = '%Y-%m-%d';
                $interval = 'day';
                break;
            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $dbFormat = '%Y-%m';
                $interval = 'month';
                break;
            case 'month':
            default:
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $dbFormat = '%Y-%m-%d';
                $interval = 'day';
                break;
        }

        $data = $query->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dbFormat}') as period"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period')
            ->toArray();

        $labels = [];
        $totals = [];

        if ($interval === 'hour') {
            for ($i = 0; $i <= 23; $i++) {
                $hour = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $labels[] = $hour;
                $totals[] = $data[$hour] ?? 0;
                if (now()->format('H') == $i && $activeFilter === 'today') break;
            }
        } elseif ($interval === 'day') {
            for ($date = clone $start; $date->lte($end); $date->addDay()) {
                $dateString = $date->format('Y-m-d');
                $labels[] = $date->format('d/m');
                $totals[] = $data[$dateString] ?? 0;
                if ($date->isToday() && $activeFilter !== 'year') break;
            }
        } elseif ($interval === 'month') {
            for ($i = 1; $i <= 12; $i++) {
                $month = clone $start;
                $month->month($i);
                $monthString = $month->format('Y-m');
                $labels[] = 'Tháng ' . $i;
                $totals[] = $data[$monthString] ?? 0;
                if ($month->isCurrentMonth()) break;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sản phẩm đã bán',
                    'data' => $totals,
                    'fill' => 'start',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hôm nay',
            'week' => '7 ngày qua',
            'month' => 'Tháng này',
            'year' => 'Năm nay',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
