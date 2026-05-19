<?php

namespace App\Services;

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

            // Extract user ID from content
            $userIdResult = $this->extractUserIdFromContent($content);
            if ($userIdResult->isError()) {
                return $userIdResult;
            }

            $userId = $userIdResult->getData();

            // Check if transaction already processed
            $existsResult = $this->transactionService->transactionExists($transactionId);
            if ($existsResult->isError()) {
                return $existsResult;
            }

            if ($existsResult->getData()['exists']) {
                return ServiceResult::success(null, 'Transaction already processed');
            }

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
