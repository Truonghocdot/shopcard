<?php

namespace App\Services;

use App\Models\Order;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    /**
     * Process Sepay webhook
     */
    public function processSepayWebhook(array $data): ServiceResult
    {
        try {
            // Validate required fields
            if (!isset($data['transferAmount'], $data['content'], $data['referenceCode'])) {
                return ServiceResult::error('Missing required fields');
            }

            $amount = $data['transferAmount'];
            $content = $data['content'];
            $transactionId = $data['referenceCode'];

            // Check if transaction already processed
            $existsResult = $this->transactionService->transactionExists($transactionId);
            if ($existsResult->isError()) {
                return $existsResult;
            }

            if ($existsResult->getData()['exists']) {
                return ServiceResult::success(null, 'Transaction already processed');
            }

            return $this->processPendingVietQrOrders($content, $transactionId, (float) $amount);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('WebhookService::processSepayWebhook error: ' . $e->getMessage());
            return ServiceResult::error('Internal server error', null, $e);
        }
    }

    protected function processPendingVietQrOrders(string $content, string $transactionId, float $amount): ServiceResult
    {
        try {
            preg_match('/QR\d+[A-Z0-9]+/i', $content, $matches);
            $paymentReference = $matches[0] ?? null;

            if (! $paymentReference) {
                return ServiceResult::error('No matching VietQR orders found');
            }

            $orders = Order::pending()
                ->where('notes', 'like', '%' . $paymentReference . '%')
                ->get();

            if ($orders->isEmpty()) {
                return ServiceResult::error('No matching VietQR orders found');
            }

            $expectedAmount = (float) $orders->sum('final_amount');
            if ($amount < $expectedAmount) {
                return ServiceResult::error('Transfer amount is lower than expected order total');
            }

            DB::beginTransaction();

            $userId = $orders->first()->user_id;

            $transactionResult = $this->transactionService->createTransaction([
                'user_id' => $userId,
                'service_type' => 1,
                'amount' => $amount,
                'status' => 1,
                'request_id' => $transactionId,
                'provider' => 'sepay',
            ]);

            if ($transactionResult->isError()) {
                DB::rollBack();
                return $transactionResult;
            }

            foreach ($orders as $order) {
                $notes = json_decode($order->notes ?? '{}', true);
                $notes['payment_status'] = 'paid';
                $notes['sepay_transaction_id'] = $transactionId;
                $notes['status_history'][] = [
                    'status' => 'paid',
                    'timestamp' => now()->toDateTimeString(),
                    'notes' => 'Paid via VietQR / SePay webhook',
                ];

                $order->update([
                    'status' => Order::STATUS_COMPLETED,
                    'notes' => json_encode($notes),
                    'completed_at' => now(),
                ]);

                $purchaser = \App\Models\User::find($order->user_id);
                if ($purchaser && $purchaser->referrer_id) {
                    \App\Jobs\ProcessAffiliateCommission::dispatch($order->id);
                }
            }

            DB::commit();

            return ServiceResult::success(null, 'VietQR order payment processed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('WebhookService::processPendingVietQrOrders error: ' . $e->getMessage());
            return ServiceResult::error('VietQR order processing failed', null, $e);
        }
    }
}
