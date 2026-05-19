<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Wallet;
use App\Types\ServiceResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        protected Order $order,
        protected CouponService $couponService,
        protected WalletService $walletService
    ) {}

    /**
     * Purchase product with full transaction handling
     */
    public function purchaseProduct(int $userId, int $productId, ?string $couponCode = null): ServiceResult
    {
        try {
            DB::beginTransaction();

            // Get product with lock
            $product = Product::where('id', $productId)
                ->where('status', Product::STATUS_UNSOLD)
                ->lockForUpdate()
                ->first();

            if (!$product) {
                DB::rollBack();
                return ServiceResult::error('Sản phẩm không tồn tại hoặc đã được bán');
            }

            // Get wallet with lock
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

            if (!$wallet) {
                DB::rollBack();
                return ServiceResult::error('Ví không tồn tại');
            }

            // Calculate amounts
            $productPrice = $product->getFinalPrice();
            $discountAmount = 0;
            $couponId = null;

            // Validate and apply coupon if provided
            if ($couponCode) {
                $couponResult = $this->couponService->validateCoupon($couponCode, $userId, $productPrice, $product);

                if ($couponResult->isError()) {
                    DB::rollBack();
                    return $couponResult;
                }

                $coupon = $couponResult->getData();
                $couponId = $coupon->id;
                $discountAmount = $coupon->calculateDiscount($productPrice);
            }

            $finalAmount = $productPrice - $discountAmount;

            // Check wallet balance
            if ($wallet->balance < $finalAmount) {
                DB::rollBack();
                return ServiceResult::error('Số dư không đủ. Vui lòng nạp thêm tiền.');
            }

            // Create order
            $order = $this->order::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'coupon_id' => $couponId,
                'order_number' => Order::generateOrderNumber(),
                'product_price' => $productPrice,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => Order::STATUS_COMPLETED,
                'wallet_balance_before' => $wallet->balance,
                'wallet_balance_after' => $wallet->balance - $finalAmount,
                'completed_at' => now(),
            ]);

            // Update product status
            $product->update(['status' => Product::STATUS_SOLD]);

            // Deduct wallet balance
            $wallet->decrement('balance', $finalAmount);

            // Record coupon usage if applicable
            if ($couponId) {
                $this->couponService->recordCouponUsage($couponId, $userId, $discountAmount);
            }

            // Process affiliate commission if user has referrer
            $purchaser = \App\Models\User::find($userId);
            if ($purchaser && $purchaser->referrer_id) {
                \App\Jobs\ProcessAffiliateCommission::dispatch($order->id);
            }

            DB::commit();

            return ServiceResult::success($order, 'Mua hàng thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OrderService::purchaseProduct error: ' . $e->getMessage());
            return ServiceResult::error('Không thể hoàn tất giao dịch', null, $e);
        }
    }

    /**
     * Create physical order paid via PayPal
     */
    public function createPhysicalOrder(
        int $userId,
        int $productId,
        array $shippingInfo,
        string $paypalTransactionId,
        ?string $couponCode = null
    ): ServiceResult {
        try {
            DB::beginTransaction();

            // Get product with lock
            $product = Product::where('id', $productId)
                ->where('status', Product::STATUS_UNSOLD)
                ->lockForUpdate()
                ->first();

            if (!$product) {
                DB::rollBack();
                return ServiceResult::error('Product does not exist or has already been sold');
            }

            // Calculate amounts
            $productPrice = $product->getFinalPrice();
            $discountAmount = 0;
            $couponId = null;

            // Validate and apply coupon if provided
            if ($couponCode) {
                $couponResult = $this->couponService->validateCoupon($couponCode, $userId, $productPrice, $product);

                if ($couponResult->isError()) {
                    DB::rollBack();
                    return $couponResult;
                }

                $coupon = $couponResult->getData();
                $couponId = $coupon->id;
                $discountAmount = $coupon->calculateDiscount($productPrice);
            }

            $finalAmount = $productPrice - $discountAmount;

            // Serialize shipping info and paypal details to notes JSON
            $notesData = [
                'shipping_info' => $shippingInfo,
                'payment_method' => 'paypal',
                'paypal_transaction_id' => $paypalTransactionId,
                'status_history' => [
                    ['status' => 'paid', 'timestamp' => now()->toDateTimeString(), 'notes' => 'Paid via PayPal successfully']
                ]
            ];

            // Create order
            $order = $this->order::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'coupon_id' => $couponId,
                'order_number' => Order::generateOrderNumber(),
                'product_price' => $productPrice,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => Order::STATUS_COMPLETED,
                'notes' => json_encode($notesData),
                'completed_at' => now(),
            ]);

            // Update product status
            $product->update(['status' => Product::STATUS_SOLD]);

            // Record coupon usage if applicable
            if ($couponId) {
                $this->couponService->recordCouponUsage($couponId, $userId, $discountAmount);
            }

            // Process affiliate commission if user has referrer
            $purchaser = \App\Models\User::find($userId);
            if ($purchaser && $purchaser->referrer_id) {
                \App\Jobs\ProcessAffiliateCommission::dispatch($order->id);
            }

            DB::commit();

            return ServiceResult::success($order, 'Order created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OrderService::createPhysicalOrder error: ' . $e->getMessage());
            return ServiceResult::error('Could not complete order creation', null, $e);
        }
    }

    /**
     * Create multiple orders from cart items, paid via PayPal.
     * Each product becomes its own Order record (1 product = 1 order).
     * Coupon discount is split proportionally across items.
     */
    public function createCartOrder(
        int $userId,
        array $productIds,
        array $shippingInfo,
        string $paypalTransactionId,
        ?string $couponCode = null
    ): ServiceResult {
        try {
            DB::beginTransaction();

            // Lock and validate all products at once
            $products = Product::whereIn('id', $productIds)
                ->where('status', Product::STATUS_UNSOLD)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($productIds)) {
                DB::rollBack();
                return ServiceResult::error('One or more products are no longer available.');
            }

            // Calculate subtotal
            $subtotal = $products->sum(fn ($p) => $p->getFinalPrice());

            // Validate coupon once against the full subtotal
            $couponId      = null;
            $totalDiscount = 0;
            $coupon        = null;

            if ($couponCode) {
                $couponResult = $this->couponService->validateCoupon($couponCode, $userId, $subtotal);

                if ($couponResult->isError()) {
                    DB::rollBack();
                    return $couponResult;
                }

                $coupon        = $couponResult->getData();
                $couponId      = $coupon->id;
                $totalDiscount = $coupon->calculateDiscount($subtotal);
            }

            $orders = [];

            foreach ($products as $product) {
                $productPrice = $product->getFinalPrice();

                // Distribute discount proportionally
                $itemDiscount = $subtotal > 0
                    ? round(($productPrice / $subtotal) * $totalDiscount, 2)
                    : 0;

                $finalAmount = max(0, $productPrice - $itemDiscount);

                $notesData = [
                    'shipping_info'        => $shippingInfo,
                    'payment_method'       => 'paypal',
                    'paypal_transaction_id' => $paypalTransactionId,
                    'status_history'       => [
                        ['status' => 'paid', 'timestamp' => now()->toDateTimeString(), 'notes' => 'Paid via PayPal']
                    ],
                ];

                $order = $this->order::create([
                    'user_id'        => $userId,
                    'product_id'     => $product->id,
                    'coupon_id'      => $couponId,
                    'order_number'   => Order::generateOrderNumber(),
                    'product_price'  => $productPrice,
                    'discount_amount' => $itemDiscount,
                    'final_amount'   => $finalAmount,
                    'status'         => Order::STATUS_COMPLETED,
                    'notes'          => json_encode($notesData),
                    'completed_at'   => now(),
                ]);

                $product->update(['status' => Product::STATUS_SOLD]);

                $orders[] = $order;
            }

            // Record coupon usage once for the whole cart
            if ($couponId && $coupon) {
                $this->couponService->recordCouponUsage($couponId, $userId, $totalDiscount);
            }

            // Dispatch affiliate commission for each order
            $purchaser = \App\Models\User::find($userId);
            if ($purchaser && $purchaser->referrer_id) {
                foreach ($orders as $order) {
                    \App\Jobs\ProcessAffiliateCommission::dispatch($order->id);
                }
            }

            DB::commit();

            return ServiceResult::success($orders, 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OrderService::createCartOrder error: ' . $e->getMessage());
            return ServiceResult::error('Could not complete order.', null, $e);
        }
    }

    /**
     * Get order by ID for specific user
     */
    public function getOrderById(int $orderId, int $userId): ServiceResult
    {
        try {
            $order = $this->order::with('product.category')
                ->where('id', $orderId)
                ->where('user_id', $userId)
                ->first();

            if (!$order) {
                return ServiceResult::error('Đơn hàng không tồn tại');
            }

            return ServiceResult::success($order);
        } catch (\Exception $e) {
            Log::error('OrderService::getOrderById error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy thông tin đơn hàng', null, $e);
        }
    }

    /**
     * Get user orders with pagination
     */
    public function getUserOrders(int $userId, int $perPage = 6): ServiceResult
    {
        try {
            $orders = $this->order::where('user_id', $userId)
                ->with('product')
                ->latest()
                ->paginate($perPage);

            return ServiceResult::success($orders);
        } catch (\Exception $e) {
            Log::error('OrderService::getUserOrders error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy danh sách đơn hàng', null, $e);
        }
    }

    /**
     * Get all user orders (without pagination)
     */
    public function getAllUserOrders(int $userId): ServiceResult
    {
        try {
            $orders = $this->order::where('user_id', $userId)
                ->with('product')
                ->latest()
                ->get();

            return ServiceResult::success($orders);
        } catch (\Exception $e) {
            Log::error('OrderService::getAllUserOrders error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy danh sách đơn hàng', null, $e);
        }
    }
}
