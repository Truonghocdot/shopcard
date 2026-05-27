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
    public const VIETQR_EXPIRY_MINUTES = 15;

    public function __construct(
        protected Order $order,
        protected CouponService $couponService,
        protected WalletService $walletService,
        protected TransactionService $transactionService
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
                ->where('quantity', '>', 0)
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
            $product->decrementStock();

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
                ->where('quantity', '>', 0)
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
            $product->decrementStock();

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
                ->where('quantity', '>', 0)
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

                $product->decrementStock();

                $orders[] = $order;
            }

            $transactionResult = $this->transactionService->createTransaction([
                'user_id' => $userId,
                'service_type' => 1,
                'amount' => $orders ? array_sum(array_map(fn ($order) => (float) $order->final_amount, $orders)) : 0,
                'status' => 1,
                'request_id' => $paypalTransactionId,
                'provider' => 'paypal',
            ]);

            if ($transactionResult->isError()) {
                DB::rollBack();
                return $transactionResult;
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

    public function createPendingVietQrCartOrder(
        int $userId,
        array $productIds,
        array $shippingInfo,
        ?string $couponCode = null
    ): ServiceResult {
        try {
            DB::beginTransaction();

            $products = Product::whereIn('id', $productIds)
                ->where('status', Product::STATUS_UNSOLD)
                ->where('quantity', '>', 0)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($productIds)) {
                DB::rollBack();
                return ServiceResult::error('One or more products are no longer available.');
            }

            $subtotal = $products->sum(fn ($p) => $p->getFinalPrice());

            $couponId = null;
            $totalDiscount = 0;
            $coupon = null;

            if ($couponCode) {
                $couponResult = $this->couponService->validateCoupon($couponCode, $userId, $subtotal);

                if ($couponResult->isError()) {
                    DB::rollBack();
                    return $couponResult;
                }

                $coupon = $couponResult->getData();
                $couponId = $coupon->id;
                $totalDiscount = $coupon->calculateDiscount($subtotal);
            }

            $orders = [];
            $paymentReference = 'QR' . $userId . strtoupper(substr(uniqid(), -6));
            $expiresAt = now()->addMinutes(self::VIETQR_EXPIRY_MINUTES);

            foreach ($products as $product) {
                $productPrice = $product->getFinalPrice();
                $itemDiscount = $subtotal > 0
                    ? round(($productPrice / $subtotal) * $totalDiscount, 2)
                    : 0;

                $finalAmount = max(0, $productPrice - $itemDiscount);

                $notesData = [
                    'shipping_info'  => $shippingInfo,
                    'payment_method' => 'vietqr',
                    'payment_status' => 'pending',
                    'payment_reference' => $paymentReference,
                    'expires_at' => $expiresAt->toDateTimeString(),
                    'status_history' => [
                        ['status' => 'pending_payment', 'timestamp' => now()->toDateTimeString(), 'notes' => 'Awaiting VietQR transfer confirmation']
                    ],
                ];

                $order = $this->order::create([
                    'user_id'         => $userId,
                    'product_id'      => $product->id,
                    'coupon_id'       => $couponId,
                    'order_number'    => Order::generateOrderNumber(),
                    'product_price'   => $productPrice,
                    'discount_amount' => $itemDiscount,
                    'final_amount'    => $finalAmount,
                    'status'          => Order::STATUS_PENDING,
                    'notes'           => json_encode($notesData),
                ]);

                $product->decrementStock();

                $orders[] = $order;
            }

            $transactionResult = $this->transactionService->createTransaction([
                'user_id' => $userId,
                'service_type' => 1,
                'amount' => $orders ? array_sum(array_map(fn ($order) => (float) $order->final_amount, $orders)) : 0,
                'status' => 0,
                'request_id' => $paymentReference,
                'provider' => 'vietqr',
            ]);

            if ($transactionResult->isError()) {
                DB::rollBack();
                return $transactionResult;
            }

            if ($couponId && $coupon) {
                $this->couponService->recordCouponUsage($couponId, $userId, $totalDiscount);
            }

            DB::commit();

            return ServiceResult::success([
                'orders' => $orders,
                'payment_reference' => $paymentReference,
                'expires_at' => $expiresAt->toDateTimeString(),
            ], 'VietQR order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OrderService::createPendingVietQrCartOrder error: ' . $e->getMessage());
            return ServiceResult::error('Could not create VietQR order.', null, $e);
        }
    }

    public function createPendingCodCartOrder(
        int $userId,
        array $productIds,
        array $shippingInfo,
        ?string $couponCode = null
    ): ServiceResult {
        try {
            DB::beginTransaction();

            $products = Product::whereIn('id', $productIds)
                ->where('status', Product::STATUS_UNSOLD)
                ->where('quantity', '>', 0)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($productIds)) {
                DB::rollBack();
                return ServiceResult::error('One or more products are no longer available.');
            }

            $subtotal = $products->sum(fn ($p) => $p->getFinalPrice());

            $couponId = null;
            $totalDiscount = 0;
            $coupon = null;

            if ($couponCode) {
                $couponResult = $this->couponService->validateCoupon($couponCode, $userId, $subtotal);

                if ($couponResult->isError()) {
                    DB::rollBack();
                    return $couponResult;
                }

                $coupon = $couponResult->getData();
                $couponId = $coupon->id;
                $totalDiscount = $coupon->calculateDiscount($subtotal);
            }

            $orders = [];

            foreach ($products as $product) {
                $productPrice = $product->getFinalPrice();
                $itemDiscount = $subtotal > 0
                    ? round(($productPrice / $subtotal) * $totalDiscount, 2)
                    : 0;

                $finalAmount = max(0, $productPrice - $itemDiscount);

                $notesData = [
                    'shipping_info'  => $shippingInfo,
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'status_history' => [
                        ['status' => 'pending_cod', 'timestamp' => now()->toDateTimeString(), 'notes' => 'Awaiting COD confirmation and delivery']
                    ],
                ];

                $order = $this->order::create([
                    'user_id'         => $userId,
                    'product_id'      => $product->id,
                    'coupon_id'       => $couponId,
                    'order_number'    => Order::generateOrderNumber(),
                    'product_price'   => $productPrice,
                    'discount_amount' => $itemDiscount,
                    'final_amount'    => $finalAmount,
                    'status'          => Order::STATUS_PENDING,
                    'notes'           => json_encode($notesData),
                ]);

                $product->decrementStock();

                $orders[] = $order;
            }

            if ($couponId && $coupon) {
                $this->couponService->recordCouponUsage($couponId, $userId, $totalDiscount);
            }

            DB::commit();

            return ServiceResult::success($orders, 'COD order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OrderService::createPendingCodCartOrder error: ' . $e->getMessage());
            return ServiceResult::error('Could not create COD order.', null, $e);
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

    public function getOrdersByIds(int $userId, array $orderIds): ServiceResult
    {
        try {
            $orders = $this->order::where('user_id', $userId)
                ->whereIn('id', $orderIds)
                ->get();

            if ($orders->isEmpty()) {
                return ServiceResult::error('Đơn hàng không tồn tại');
            }

            return ServiceResult::success($orders);
        } catch (\Exception $e) {
            Log::error('OrderService::getOrdersByIds error: ' . $e->getMessage());
            return ServiceResult::error('Không thể lấy danh sách đơn hàng', null, $e);
        }
    }

    public function cancelExpiredPendingOrders(?string $paymentMethod = null): ServiceResult
    {
        try {
            DB::beginTransaction();

            $orders = $this->order::where('status', Order::STATUS_PENDING)
                ->when($paymentMethod, function ($query) use ($paymentMethod) {
                    $query->where('notes', 'like', '%"payment_method":"' . $paymentMethod . '"%');
                })
                ->get()
                ->filter(function (Order $order) {
                    $notes = json_decode($order->notes ?? '{}', true);
                    $expiresAt = $notes['expires_at'] ?? null;

                    return $expiresAt && now()->greaterThanOrEqualTo($expiresAt);
                });

            $cancelledCount = 0;
            $restockedCount = 0;

            foreach ($orders as $order) {
                $notes = json_decode($order->notes ?? '{}', true);

                if ($order->product) {
                    $order->product?->incrementStock();
                    $restockedCount++;
                }

                $notes['payment_status'] = 'expired';
                $notes['status_history'][] = [
                    'status' => 'expired',
                    'timestamp' => now()->toDateTimeString(),
                    'notes' => 'Pending order expired and stock was restored',
                ];

                $order->update([
                    'status' => Order::STATUS_CANCELLED,
                    'notes' => json_encode($notes),
                ]);

                if (($notes['payment_method'] ?? null) === 'vietqr' && ! empty($notes['payment_reference'])) {
                    \App\Models\Transaction::where('request_id', $notes['payment_reference'])
                        ->where('provider', 'vietqr')
                        ->where('status', \App\Models\Transaction::STATUS_PENDING)
                        ->update(['status' => \App\Models\Transaction::STATUS_FAILED]);
                }

                $cancelledCount++;
            }

            DB::commit();

            return ServiceResult::success([
                'cancelled_orders' => $cancelledCount,
                'restocked_products' => $restockedCount,
            ], 'Expired pending orders cleaned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OrderService::cancelExpiredPendingOrders error: ' . $e->getMessage());
            return ServiceResult::error('Could not clean expired pending orders.', null, $e);
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
