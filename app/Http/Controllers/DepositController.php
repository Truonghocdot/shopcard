<?php

namespace App\Http\Controllers;

use App\Services\DepositService;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function __construct(protected DepositService $depositService) {}

    public function index()
    {
        $userId = Auth::check() ? Auth::id() : null;

        // Get recent deposits
        $transactionsResult = $this->depositService->getRecentDeposits($userId, 10);
        $transactions = $transactionsResult->isSuccess() ? $transactionsResult->getData() : collect();

        // Get bank info
        $bankInfoResult = $this->depositService->getBankInfo();

        if ($bankInfoResult->isError()) {
            abort(500, $bankInfoResult->getMessage());
        }

        $bankInfo = $bankInfoResult->getData();

        return view('deposit', [
            'transactions' => $transactions,
            'bankBin' => $bankInfo['bank_bin'],
            'bankNumber' => $bankInfo['bank_number'],
            'bankName' => $bankInfo['bank_name'],
            'banking' => $bankInfo['banking'],
        ]);
    }
}
