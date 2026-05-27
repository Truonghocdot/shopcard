<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Illuminate\Console\Command;

class ClearExpiredPendingOrders extends Command
{
    protected $signature = 'orders:clear-expired-pending {--method=vietqr : Filter by payment method (vietqr|cod)}';

    protected $description = 'Cancel expired pending orders, reverse stock, and fail pending transactions when needed.';

    public function __construct(protected OrderService $orderService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $method = $this->option('method');
        $method = $method ?: null;

        $result = $this->orderService->cancelExpiredPendingOrders($method);

        if ($result->isError()) {
            $this->error($result->getMessage());
            return self::FAILURE;
        }

        $data = $result->getData();

        $this->info('Expired pending order cleanup completed.');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Payment method', $method ?? 'all'],
                ['Cancelled orders', $data['cancelled_orders'] ?? 0],
                ['Restocked products', $data['restocked_products'] ?? 0],
            ]
        );

        return self::SUCCESS;
    }
}
