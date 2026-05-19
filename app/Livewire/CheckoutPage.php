<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Services\CouponService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Checkout')]
class CheckoutPage extends Component
{
    public string $couponCode    = '';
    public ?object $appliedCoupon = null;
    public float $discount       = 0;
    public string $couponMessage = '';
    public bool $couponValid     = false;

    // Shipping
    public string $shipping_name        = '';
    public string $shipping_phone       = '';
    public string $shipping_email       = '';
    public string $shipping_address     = '';
    public string $shipping_city        = '';
    public string $shipping_postal_code = '';
    public string $shipping_country     = 'United States';

    protected CartService $cartService;
    protected CouponService $couponService;
    protected OrderService $orderService;

    protected $rules = [
        'shipping_name'        => 'required|string|max:100',
        'shipping_phone'       => 'required|string|max:20',
        'shipping_email'       => 'required|email|max:150',
        'shipping_address'     => 'required|string|max:255',
        'shipping_city'        => 'required|string|max:100',
        'shipping_postal_code' => 'required|string|max:20',
        'shipping_country'     => 'required|string|max:100',
    ];

    public function boot(
        CartService $cartService,
        CouponService $couponService,
        OrderService $orderService
    ): void {
        $this->cartService  = $cartService;
        $this->couponService = $couponService;
        $this->orderService  = $orderService;
    }

    public function mount(): void
    {
        if ($this->cartService->isEmpty()) {
            redirect()->route('cart');
            return;
        }

        $user = Auth::user();
        if ($user) {
            $this->shipping_name  = $user->name;
            $this->shipping_email = $user->email;
            $this->shipping_phone = $user->phone ?? '';
        }

        // Restore coupon from cart page if present
        if ($code = session('checkout_coupon')) {
            $this->couponCode = $code;
            $this->applyCoupon();
        }
    }

    // ── Computed properties ──────────────────────────────────────────────────

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

    // ── Coupon ───────────────────────────────────────────────────────────────

    public function applyCoupon(): void
    {
        $this->couponMessage = '';
        $this->couponValid   = false;

        if (empty(trim($this->couponCode))) {
            $this->couponMessage = __('cart.enter_coupon');
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

    // ── Payment ──────────────────────────────────────────────────────────────

    /**
     * Called from JS after PayPal captures the payment.
     */
    public function processPayPalPayment(string $paypalTransactionId): mixed
    {
        $this->validate();

        if ($this->cartService->isEmpty()) {
            session()->flash('error', __('cart.empty'));
            return null;
        }

        $shippingInfo = [
            'name'        => $this->shipping_name,
            'phone'       => $this->shipping_phone,
            'email'       => $this->shipping_email,
            'address'     => $this->shipping_address,
            'city'        => $this->shipping_city,
            'postal_code' => $this->shipping_postal_code,
            'country'     => $this->shipping_country,
        ];

        $couponCode = $this->couponValid && $this->appliedCoupon
            ? $this->appliedCoupon->code
            : null;

        $productIds = $this->cartService->productIds();

        $result = $this->orderService->createCartOrder(
            Auth::id(),
            $productIds,
            $shippingInfo,
            $paypalTransactionId,
            $couponCode
        );

        if ($result->isError()) {
            session()->flash('error', $result->getMessage());
            return null;
        }

        // Clear cart & coupon session
        $this->cartService->clear();
        session()->forget('checkout_coupon');

        $orders = $result->getData();
        $firstOrder = is_array($orders) ? $orders[0] : $orders;

        return redirect()->route('purchase.success', $firstOrder->id);
    }

    private function resetCoupon(): void
    {
        $this->appliedCoupon = null;
        $this->discount      = 0;
        $this->couponValid   = false;
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }
}
