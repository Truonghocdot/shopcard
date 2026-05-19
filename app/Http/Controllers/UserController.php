<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected OrderService $orderService
    ) {}

    public function profile()
    {
        $ordersResult = $this->orderService->getAllUserOrders(Auth::id());
        $orders = $ordersResult->isSuccess() ? $ordersResult->getData() : collect();

        return view('user.profile', compact('orders'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
        ];

        if ($request->filled('new_password')) {
            $data['new_password'] = $request->new_password;
        }

        $result = $this->userService->updateProfile(Auth::id(), $data);

        if ($result->isError()) {
            return back()->withErrors(['error' => $result->getMessage()]);
        }

        return back()->with('success', $result->getMessage());
    }
}
