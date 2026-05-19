<?php

namespace App\Http\Controllers;

use App\Services\WebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SepayWebhookController extends Controller
{
    public function __construct(protected WebhookService $webhookService) {}

    /**
     * Handle Sepay webhook
     */
    public function handle(Request $request)
    {
        // Log webhook request for debugging
        Log::info('Sepay Webhook Received', $request->all());

        $result = $this->webhookService->processSepayWebhook($request->all());

        if ($result->isError()) {
            $statusCode = $result->getMessage() === 'Missing required fields' ? 400 : 500;

            return response()->json([
                'error' => $result->getMessage()
            ], $statusCode);
        }

        return response()->json([
            'success' => true,
            'message' => $result->getMessage()
        ], 200);
    }
}
