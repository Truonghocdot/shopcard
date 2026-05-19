<?php

namespace App\Livewire\User;

use App\Services\TransactionService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class TransactionHistory extends Component
{
    use WithPagination;

    public $filterType = '';
    public $filterStatus = '';

    protected $transactionService;

    public function boot(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function render()
    {
        $filters = [
            'service_type' => $this->filterType,
            'status' => $this->filterStatus,
        ];

        $transactionsResult = $this->transactionService->getUserTransactions(Auth::id(), $filters, 10);
        $transactions = $transactionsResult->isSuccess()
            ? $transactionsResult->getData()
            : collect();

        return view('livewire.user.transaction-history', [
            'transactions' => $transactions
        ]);
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
}
