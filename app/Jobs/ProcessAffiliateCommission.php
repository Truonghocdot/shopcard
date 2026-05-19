<?php

namespace App\Jobs;

use App\Services\AffiliateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAffiliateCommission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $orderId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(AffiliateService $affiliateService): void
    {
        $result = $affiliateService->processCommission($this->orderId);

        if ($result->isError()) {
            Log::error("ProcessAffiliateCommission job failed: " . $result->getMessage());
            throw new \Exception($result->getMessage()); // Re-throw to trigger retry
        }
    }
}
