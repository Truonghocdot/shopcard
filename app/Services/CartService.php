<?php

namespace App\Services;

use App\Models\Product;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\Session;

/**
 * Session-based cart service.
 * Cart structure in session:
 *   cart => [ product_id => [ 'product_id', 'title', 'slug', 'price', 'image' ], ... ]
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    /**
     * Add a product to the cart.
     * Each product can only appear once (TCG cards are unique items).
     */
    public function add(int $productId): ServiceResult
    {
        $product = Product::where('id', $productId)
            ->where('status', Product::STATUS_UNSOLD)
            ->where('quantity', '>', 0)
            ->with('category')
            ->first();

        if (!$product) {
            return ServiceResult::error(__('cart.product_not_found'));
        }

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            return ServiceResult::error(__('cart.already_in_cart'));
        }

        $cart[$productId] = [
            'product_id' => $product->id,
            'title'      => $product->title,
            'slug'       => $product->slug,
            'price'      => $product->getFinalPrice(),
            'sell_price' => $product->sell_price,
            'image'      => $product->images[0] ?? null,
            'category'   => $product->category->title ?? '',
        ];

        Session::put(self::SESSION_KEY, $cart);

        return ServiceResult::success($cart, __('cart.added'));
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(int $productId): ServiceResult
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            return ServiceResult::error(__('cart.not_in_cart'));
        }

        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);

        return ServiceResult::success($cart, __('cart.removed'));
    }

    /**
     * Clear the entire cart.
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Get all cart items.
     */
    public function getItems(): array
    {
        return $this->getCart();
    }

    /**
     * Get cart item count.
     */
    public function count(): int
    {
        return count($this->getCart());
    }

    /**
     * Get cart subtotal.
     */
    public function subtotal(): float
    {
        return array_sum(array_column($this->getCart(), 'price'));
    }

    /**
     * Check if cart is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->getCart());
    }

    /**
     * Check if a product is in the cart.
     */
    public function has(int $productId): bool
    {
        return isset($this->getCart()[$productId]);
    }

    /**
     * Get product IDs in cart.
     */
    public function productIds(): array
    {
        return array_keys($this->getCart());
    }

    private function getCart(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }
}
