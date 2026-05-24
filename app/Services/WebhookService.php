<?php

namespace App\Services;

use App\Models\Order;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    public function __construct(
        protected TransactionService $transactionService,
        protected WalletService $walletService
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

            $vietQrOrderResult = $this->processPendingVietQrOrders($content, $transactionId, (float) $amount);
            if ($vietQrOrderResult->isSuccess()) {
                return $vietQrOrderResult;
            }

            // Extract user ID from content
            $userIdResult = $this->extractUserIdFromContent($content);
            if ($userIdResult->isError()) {
                return $userIdResult;
            }

            $userId = $userIdResult->getData();

            // Process transaction
            DB::beginTransaction();

            // Create transaction record
            $transactionResult = $this->transactionService->createTransaction([
                'user_id' => $userId,
                'service_type' => 0, // topup
                'amount' => $amount,
                'status' => 1, // success
                'request_id' => $transactionId,
                'provider' => 'sepay',
            ]);

            if ($transactionResult->isError()) {
                DB::rollBack();
                return $transactionResult;
            }

            // Update wallet balance
            $depositResult = $this->walletService->deposit($userId, $amount);
            if ($depositResult->isError()) {
                DB::rollBack();
                return $depositResult;
            }

            DB::commit();

            Log::info('Sepay Webhook: Transaction processed successfully', [
                'user_id' => $userId,
                'amount' => $amount,
            ]);

            return ServiceResult::success(null, 'Transaction processed successfully');
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
                return ServiceResult::error('No VietQR order reference found');
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

    /**
     * Extract user ID from webhook content
     * Expected format: "rabbytcg 123" or "vanhfco 123"
     */
    protected function extractUserIdFromContent(string $content): ServiceResult
    {
        try {
            if (!preg_match('/(?:vanhfco|rabbytcg)\s+(\d+)/i', $content, $matches)) {
                Log::warning('WebhookService: Invalid content format', ['content' => $content]);
                return ServiceResult::error('Invalid content format');
            }

            $userId = (int) $matches[1];

            return ServiceResult::success($userId);
        } catch (\Exception $e) {
            Log::error('WebhookService::extractUserIdFromContent error: ' . $e->getMessage());
            return ServiceResult::error('Cannot extract user ID from content', null, $e);
        }
    }
}
