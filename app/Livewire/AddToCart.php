<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

/**
 * Inline "Add to Cart" button component used on product detail pages.
 * Emits a browser event so the cart icon in the header can update its count.
 */
class AddToCart extends Component
{
    public int $productId;
    public bool $inCart = false;
    public bool $isSoldOut = false;

    protected CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    public function mount(int $productId): void
    {
        $this->productId = $productId;
        $this->inCart    = $this->cartService->has($productId);
        $product = \App\Models\Product::find($productId);
        $this->isSoldOut = ! $product || $product->quantity <= 0;
    }

    public function add(): mixed
    {
        if ($this->isSoldOut) {
            session()->flash('cart_error', __('sold_out'));
            return null;
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $result = $this->cartService->add($this->productId);

        if ($result->isError()) {
            session()->flash('cart_error', $result->getMessage());
            return null;
        }

        $this->inCart = true;
        $this->dispatch('cart-updated', count: $this->cartService->count());

        return null;
    }

    public function goToCart(): mixed
    {
        return redirect()->route('cart');
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
