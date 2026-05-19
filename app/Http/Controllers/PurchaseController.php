<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function success($id)
    {
        $orderResult = $this->orderService->getOrderById($id, Auth::id());

        if ($orderResult->isError()) {
            abort(404, $orderResult->getMessage());
        }

        $order = $orderResult->getData();

        return view('purchase.success', compact('order'));
    }
}
