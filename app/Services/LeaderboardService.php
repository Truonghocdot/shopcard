<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LeaderboardService
{
    /**
     * Get top spenders with total amount spent
     *
     * @param int $limit Number of top users to retrieve
     * @return \Illuminate\Support\Collection
     */
    public function getTopSpenders(int $limit = 10)
    {
        return Cache::remember('leaderboard:top_spenders:' . $limit, 300, function () use ($limit) {
            return User::select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('SUM(orders.final_amount) as total_spent'),
                DB::raw('COUNT(orders.id) as total_orders')
            )
                ->join('orders', 'users.id', '=', 'orders.user_id')
                ->where('orders.status', 1) // STATUS_COMPLETED
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderByDesc('total_spent')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Clear leaderboard cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget('leaderboard:top_spenders:10');
        Cache::forget('leaderboard:top_spenders:20');
        Cache::forget('leaderboard:top_spenders:50');
    }
}
