<?php

namespace App\Livewire\User;

use App\Services\OrderService;
use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class PurchasedProducts extends Component
{
    use WithPagination;

    public $selectedOrder = null;
    public $showModal = false;

    protected $paginationTheme = 'tailwind';

    protected $orderService;
    protected $userService;

    public function boot(
        OrderService $orderService,
        UserService $userService
    ) {
        $this->orderService = $orderService;
        $this->userService = $userService;
    }

    public function viewDetails($orderId)
    {
        $orderResult = $this->orderService->getOrderById($orderId, Auth::id());

        if ($orderResult->isError()) {
            session()->flash('error', $orderResult->getMessage());
            return;
        }

        $this->selectedOrder = $orderResult->getData();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedOrder = null;
    }

    public function render()
    {
        $purchasedProductsResult = $this->orderService->getUserOrders(Auth::id(), 6);
        $purchasedProducts = $purchasedProductsResult->isSuccess()
            ? $purchasedProductsResult->getData()
            : collect();

        return view('livewire.user.purchased-products', [
            'purchasedProducts' => $purchasedProducts
        ]);
    }
}
