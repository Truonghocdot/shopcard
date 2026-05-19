<div>
    <div class="glass rounded-3xl border border-white/10 overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-white/5 bg-white/2">
            <h2 class="text-white text-lg font-black uppercase tracking-wider">{{ __('purchased_cards') }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/2 text-neutral-400 text-xs font-black uppercase tracking-widest border-b border-white/5">
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Card Name</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Date Purchased</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-neutral-300">
                    @forelse($purchasedProducts as $order)
                    <tr class="text-sm hover:bg-white/2 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-indigo-400">#{{ $order->order_number }}</td>
                        <td class="px-6 py-4">
                            <span class="font-black text-white block">{{ $order->product->title ?? 'Deleted Card' }}</span>
                            <span class="text-[10px] text-neutral-500 uppercase tracking-widest font-bold">{{ $order->product->category->title ?? 'TCG' }}</span>
                        </td>
                        <td class="px-6 py-4 font-black text-primary">
                            {{ number_format($order->final_amount) }}đ
                        </td>
                        <td class="px-6 py-4 text-neutral-400 font-medium">
                            {{ $order->created_at->format('M d, Y - H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($order->status == 1)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-400/10 text-emerald-400 border border-emerald-400/20">Completed</span>
                            @elseif($order->status == 2)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-pink-500/10 text-pink-500 border border-pink-500/20">Cancelled</span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-yellow-400/10 text-yellow-400 border border-yellow-400/20">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="viewDetails({{ $order->id }})" class="text-primary font-black uppercase tracking-widest text-[10px] hover:underline transition-colors flex items-center gap-1 justify-end ml-auto">
                                <span class="material-icons text-sm">visibility</span> View Details
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-neutral-500">
                            <div class="flex flex-col items-center gap-3">
                                <span class="material-icons text-5xl text-neutral-700 animate-bounce">shopping_cart_off</span>
                                <p class="text-xs uppercase font-black tracking-widest">You have not purchased any cards yet</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($purchasedProducts->hasPages())
        <div class="p-6 border-t border-white/5 bg-white/2">
            {{ $purchasedProducts->links() }}
        </div>
        @endif

        <!-- Order Detail Modal -->
        @if($showModal && $selectedOrder)
        @php
            $notes = json_decode($selectedOrder->notes, true);
            $shipping = $notes['shipping_info'] ?? null;
            $paypalTx = $notes['paypal_transaction_id'] ?? null;
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-md"
            wire:click.self="closeModal">
            <div class="glass rounded-4xl w-full max-w-3xl overflow-hidden shadow-2xl animate-fade-in-up max-h-[90vh] overflow-y-auto border border-white/10">
                <div class="p-6 border-b border-white/5 flex justify-between items-center sticky top-0 bg-[#080A0F]/90 backdrop-blur-md z-10">
                    <h3 class="text-xl font-black text-white uppercase tracking-tight flex items-center gap-2">
                        <span class="material-icons text-primary">receipt_long</span>
                        Order Details #{{ $selectedOrder->order_number }}
                    </h3>
                    <button wire:click="closeModal" class="text-neutral-400 hover:text-white p-2 rounded-full hover:bg-white/5 transition-colors">
                        <span class="material-icons">close</span>
                    </button>
                </div>

                <div class="p-8 space-y-8">
                    <!-- Product Info -->
                    <div class="flex flex-col sm:flex-row gap-6 bg-white/2 rounded-2xl border border-white/5 p-6">
                        <div class="w-24 h-24 rounded-xl overflow-hidden shrink-0 border border-white/10 bg-neutral-950">
                            @if($selectedOrder->product->images && count($selectedOrder->product->images) > 0 && $selectedOrder->product->images[0])
                            <img src="{{ url('storage/'.$selectedOrder->product->images[0]) }}"
                                alt="{{ $selectedOrder->product->title ?? 'Card' }}"
                                class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-neutral-600 bg-neutral-900">
                                <span class="material-icons">image</span>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 flex flex-col justify-center">
                            <h4 class="font-black text-xl text-white mb-2 leading-tight">{{ $selectedOrder->product->title ?? 'Deleted Card' }}</h4>
                            <p class="text-[10px] text-neutral-500 font-bold uppercase tracking-widest mb-3 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                {{ $selectedOrder->product->category->title ?? 'TCG Collectibles' }}
                            </p>
                            <div class="flex items-center gap-4 text-xs font-bold">
                                <div class="text-neutral-400">
                                    Original Price: <span class="line-through">{{ number_format($selectedOrder->product_price) }}đ</span>
                                </div>
                                <div class="text-primary font-black">
                                    Total Paid: {{ number_format($selectedOrder->final_amount) }}đ
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TCG Card Details -->
                    <div class="bg-white/2 rounded-2xl border border-white/5 p-6 space-y-4">
                        <h4 class="font-black text-xs text-white uppercase tracking-widest flex items-center gap-2 border-b border-white/5 pb-3">
                            <span class="material-icons text-primary text-sm">stars</span>
                            Card Grading & Specs
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div class="bg-neutral-950/40 p-3.5 rounded-xl border border-white/5">
                                <span class="text-neutral-500 block mb-1 uppercase tracking-widest text-[9px] font-black">{{ __('psa_cert_serial') }}</span>
                                <span class="font-mono font-black text-indigo-400 select-all">{{ $selectedOrder->product->username ?? 'N/A' }}</span>
                            </div>
                            <div class="bg-neutral-950/40 p-3.5 rounded-xl border border-white/5">
                                <span class="text-neutral-500 block mb-1 uppercase tracking-widest text-[9px] font-black">{{ __('card_condition') }}</span>
                                <span class="font-bold text-neutral-200">{{ $selectedOrder->product->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="bg-neutral-950/40 p-3.5 rounded-xl border border-white/5">
                                <span class="text-neutral-500 block mb-1 uppercase tracking-widest text-[9px] font-black">{{ __('card_language') }}</span>
                                <span class="font-bold text-neutral-200">{{ $selectedOrder->product->password ?? 'N/A' }}</span>
                            </div>
                            <div class="bg-neutral-950/40 p-3.5 rounded-xl border border-white/5">
                                <span class="text-neutral-500 block mb-1 uppercase tracking-widest text-[9px] font-black">{{ __('card_set_expansion') }}</span>
                                <span class="font-bold text-neutral-200">{{ $selectedOrder->product->email ?? 'N/A' }}</span>
                            </div>
                            <div class="bg-neutral-950/40 p-3.5 rounded-xl border border-white/5 md:col-span-2">
                                <span class="text-neutral-500 block mb-1 uppercase tracking-widest text-[9px] font-black">{{ __('card_rarity') }}</span>
                                <span class="font-black text-primary">{{ $selectedOrder->product->password2 ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping details if available -->
                    @if($shipping)
                    <div class="bg-white/2 rounded-2xl border border-white/5 p-6 space-y-4">
                        <h4 class="font-black text-xs text-white uppercase tracking-widest flex items-center gap-2 border-b border-white/5 pb-3">
                            <span class="material-icons text-primary text-sm">local_shipping</span>
                            Shipping Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-bold text-neutral-200">
                            <div>
                                <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('full_name') }}</span>
                                <span>{{ $shipping['name'] }}</span>
                            </div>
                            <div>
                                <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('phone_number') }}</span>
                                <span>{{ $shipping['phone'] }}</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('email_address') }}</span>
                                <span>{{ $shipping['email'] }}</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('shipping_address') }}</span>
                                <span>{{ $shipping['address'] }}, {{ $shipping['city'] }}, {{ $shipping['postal_code'] }}, {{ $shipping['country'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- PayPal Details -->
                    @if($paypalTx)
                    <div class="bg-white/2 rounded-2xl border border-white/5 p-6 space-y-4">
                        <h4 class="font-black text-xs text-white uppercase tracking-widest flex items-center gap-2 border-b border-white/5 pb-3">
                            <span class="material-icons text-primary text-sm">payment</span>
                            Payment Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">Method</span>
                                <span class="font-bold text-neutral-200">PayPal / Credit Card</span>
                            </div>
                            <div>
                                <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">PayPal Transaction ID</span>
                                <span class="font-mono font-bold text-indigo-400 select-all">{{ $paypalTx }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Transaction details -->
                    <div class="grid grid-cols-2 gap-4 text-xs font-bold text-neutral-400 bg-white/2 p-6 rounded-2xl border border-white/5">
                        <div>
                            <span class="text-neutral-500 block uppercase tracking-widest text-[9px] font-black mb-1">DATE OF PURCHASE</span>
                            <span class="text-neutral-200">{{ $selectedOrder->created_at->format('M d, Y - H:i:s') }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block uppercase tracking-widest text-[9px] font-black mb-1">ORDER STATUS</span>
                            @if($selectedOrder->status == 1)
                            <span class="text-emerald-400">Completed</span>
                            @else
                            <span class="text-yellow-400">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-white/5 bg-white/2 flex justify-end sticky bottom-0 z-10">
                    <button wire:click="closeModal" class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white font-black text-xs uppercase tracking-widest rounded-xl transition active:scale-95">
                        Close
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        @keyframes fade-in-up {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.3s ease-out forwards;
        }

        .select-all {
            -webkit-user-select: all;
            user-select: all;
        }
    </style>
</div>