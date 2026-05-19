<?php

namespace App\Livewire;

use App\Services\OrderService;
use App\Services\CouponService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Checkout')]
class CheckoutPage extends Component
{
    public $product;
    public $couponCode = '';
    public $appliedCoupon = null;
    public $discount = 0;
    public $couponMessage = '';
    public $couponValid = false;

    // Shipping Properties
    public $shipping_name = '';
    public $shipping_phone = '';
    public $shipping_email = '';
    public $shipping_address = '';
    public $shipping_city = '';
    public $shipping_postal_code = '';
    public $shipping_country = 'United States';

    protected $orderService;
    protected $couponService;
    protected $productService;

    protected $rules = [
        'shipping_name' => 'required|string|max:100',
        'shipping_phone' => 'required|string|max:20',
        'shipping_email' => 'required|email|max:150',
        'shipping_address' => 'required|string|max:255',
        'shipping_city' => 'required|string|max:100',
        'shipping_postal_code' => 'required|string|max:20',
        'shipping_country' => 'required|string|max:100',
    ];

    public function boot(
        OrderService $orderService,
        CouponService $couponService,
        ProductService $productService
    ) {
        $this->orderService = $orderService;
        $this->couponService = $couponService;
        $this->productService = $productService;
    }

    public function mount($slug)
    {
        $productResult = $this->productService->getProductBySlug($slug, true);

        if ($productResult->isError()) {
            abort(404, $productResult->getMessage());
        }

        $this->product = $productResult->getData();

        // Check if product is still available
        if ($this->product->status !== \App\Models\Product::STATUS_UNSOLD) {
            abort(404, 'Product is no longer available');
        }

        // Initialize user details if logged in
        $user = Auth::user();
        if ($user) {
            $this->shipping_name = $user->name;
            $this->shipping_email = $user->email;
            $this->shipping_phone = $user->phone ?? '';
        }
    }

    public function getTitle()
    {
        return __('confirm_payment') . ' - ' . $this->product->title;
    }

    public function getOriginalPriceProperty()
    {
        return $this->product->getFinalPrice();
    }

    public function getFinalAmountProperty()
    {
        return $this->originalPrice - $this->discount;
    }

    public function getFinalAmountUSDProperty()
    {
        // Conversion rate: 1 USD = 25,000 VND. Round to 2 decimal places.
        return round($this->finalAmount / 25000, 2);
    }

    public function applyCoupon()
    {
        $this->couponMessage = '';
        $this->couponValid = false;

        if (empty(trim($this->couponCode))) {
            $this->couponMessage = 'Please enter a coupon code';
            return;
        }

        $validationResult = $this->couponService->validateCoupon(
            trim($this->couponCode),
            Auth::id(),
            $this->originalPrice,
            $this->product
        );

        if ($validationResult->isError()) {
            $this->couponMessage = $validationResult->getMessage();
            $this->resetCoupon();
            return;
        }

        // Apply coupon successfully
        $this->appliedCoupon = $validationResult->getData();
        $this->discount = $this->appliedCoupon->calculateDiscount($this->originalPrice);
        $this->couponValid = true;
        $this->couponMessage = $validationResult->getMessage();
    }

    public function removeCoupon()
    {
        $this->resetCoupon();
        $this->couponCode = '';
        $this->couponMessage = '';
    }

    private function resetCoupon()
    {
        $this->appliedCoupon = null;
        $this->discount = 0;
        $this->couponValid = false;
    }

    /**
     * Process order creation after successful PayPal transaction
     */
    public function processPayPalPayment($paypalTransactionId)
    {
        $this->validate();

        $couponCode = $this->appliedCoupon ? $this->appliedCoupon->code : null;

        $shippingInfo = [
            'name' => $this->shipping_name,
            'phone' => $this->shipping_phone,
            'email' => $this->shipping_email,
            'address' => $this->shipping_address,
            'city' => $this->shipping_city,
            'postal_code' => $this->shipping_postal_code,
            'country' => $this->shipping_country,
        ];

        $result = $this->orderService->createPhysicalOrder(
            Auth::id(),
            $this->product->id,
            $shippingInfo,
            $paypalTransactionId,
            $couponCode
        );

        if ($result->isError()) {
            session()->flash('error', $result->getMessage());
            return;
        }

        $order = $result->getData();

        return redirect()->route('purchase.success', $order->id);
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }
}
