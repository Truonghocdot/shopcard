<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Services\CouponService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
#[Title('Cart')]
class CartPage extends Component
{
    public string $couponCode  = '';
    public ?object $appliedCoupon = null;
    public float $discount     = 0;
    public string $couponMessage = '';
    public bool $couponValid   = false;

    protected CartService $cartService;
    protected CouponService $couponService;

    public function boot(CartService $cartService, CouponService $couponService): void
    {
        $this->cartService  = $cartService;
        $this->couponService = $couponService;
    }

    public function getItemsProperty(): array
    {
        return $this->cartService->getItems();
    }

    public function getSubtotalProperty(): float
    {
        return $this->cartService->subtotal();
    }

    public function getFinalAmountProperty(): float
    {
        return max(0, $this->subtotal - $this->discount);
    }

    public function getFinalAmountUSDProperty(): float
    {
        return round($this->finalAmount / 25000, 2);
    }

    public function getCountProperty(): int
    {
        return $this->cartService->count();
    }

    public function removeItem(int $productId): void
    {
        $this->cartService->remove($productId);

        // Re-validate coupon if applied
        if ($this->couponValid) {
            $this->revalidateCoupon();
        }
    }

    public function applyCoupon(): void
    {
        $this->couponMessage = '';
        $this->couponValid   = false;

        if (empty(trim($this->couponCode))) {
            $this->couponMessage = __('cart.enter_coupon');
            return;
        }

        if ($this->cartService->isEmpty()) {
            $this->couponMessage = __('cart.empty_for_coupon');
            return;
        }

        $result = $this->couponService->validateCoupon(
            trim($this->couponCode),
            Auth::id(),
            $this->subtotal
        );

        if ($result->isError()) {
            $this->couponMessage = $result->getMessage();
            $this->resetCoupon();
            return;
        }

        $this->appliedCoupon = $result->getData();
        $this->discount      = $this->appliedCoupon->calculateDiscount($this->subtotal);
        $this->couponValid   = true;
        $this->couponMessage = $result->getMessage();
    }

    public function removeCoupon(): void
    {
        $this->resetCoupon();
        $this->couponCode    = '';
        $this->couponMessage = '';
    }

    public function proceedToCheckout()
    {
        if ($this->cartService->isEmpty()) {
            session()->flash('error', __('cart.empty'));
            return;
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Store coupon in session for checkout page
        if ($this->couponValid && $this->appliedCoupon) {
            session(['checkout_coupon' => $this->appliedCoupon->code]);
        } else {
            session()->forget('checkout_coupon');
        }

        return redirect()->route('checkout');
    }

    private function resetCoupon(): void
    {
        $this->appliedCoupon = null;
        $this->discount      = 0;
        $this->couponValid   = false;
    }

    private function revalidateCoupon(): void
    {
        if (!$this->appliedCoupon) {
            return;
        }

        $result = $this->couponService->validateCoupon(
            $this->appliedCoupon->code,
            Auth::id(),
            $this->subtotal
        );

        if ($result->isError()) {
            $this->resetCoupon();
            $this->couponMessage = __('cart.coupon_revalidation_failed');
        } else {
            $this->discount = $this->appliedCoupon->calculateDiscount($this->subtotal);
        }
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
