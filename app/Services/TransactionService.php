<?php

namespace App\Services;

use App\Models\Transaction;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function __construct(protected Transaction $transaction) {}

    /**
     * Create new transaction
     */
    public function createTransaction(array $data): ServiceResult
    {
        try {
            $transaction = $this->transaction::create($data);

            return ServiceResult::success($transaction, 'Tạo giao dịch thành công');
        } catch (\Exception $e) {
            Log::error('TransactionService::createTransaction error: ' . $e->getMessage());
            return ServiceResult::error('Không thể tạo giao dịch', null, $e);
        }
    }

    /**
     * Get user transactions with filters
     */
    public function getUserTransactions(int $userId, array $filters = [], int $perPage = 10): ServiceResult
    {
        try {
            $query = $this->transaction::where('user_id', $userId);

            // Filter by service type
            if (isset($filters['service_type']) && $filters['service_type'] !== '') {
                $query->where('service_type', $filters['service_type']);
            }

            // Filter by status
            if (isset($filters['status']) && $filters['status'] !== '') {
                $query->where('status', $filters['status']);
            }

            $transactions = $query->latest()->paginate($perPage);

            return ServiceResult::success($transactions);
        } catch (\Exception $e) {
            Log::error('TransactionService::getUserTransactions error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy lịch sử giao dịch', null, $e);
        }
    }

    /**
     * Get recent deposits for user
     */
    public function getRecentDeposits(int $userId, int $limit = 10): ServiceResult
    {
        try {
            $transactions = $this->transaction::where('user_id', $userId)
                ->where('service_type', 0) // topup only
                ->latest()
                ->take($limit)
                ->get();

            return ServiceResult::success($transactions);
        } catch (\Exception $e) {
            Log::error('TransactionService::getRecentDeposits error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy lịch sử nạp tiền', null, $e);
        }
    }

    /**
     * Check if transaction exists by request ID
     */
    public function transactionExists(string $requestId): ServiceResult
    {
        try {
            $exists = $this->transaction::where('request_id', $requestId)->exists();

            return ServiceResult::success(['exists' => $exists]);
        } catch (\Exception $e) {
            Log::error('TransactionService::transactionExists error: ' . $e->getMessage());
            return ServiceResult::error('Không thể kiểm tra giao dịch', null, $e);
        }
    }
}
