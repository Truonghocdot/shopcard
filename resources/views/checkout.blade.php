@extends('layouts.app')

@section('title', __('confirm_payment') . ' - ' . $product->title)

@section('content')
<div class="max-w-4xl mx-auto relative z-10 px-4 py-8">
    <div class="mb-12 text-center relative">
        <!-- Decorative background glow - Darker for better contrast -->
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-secondary/10 blur-[100px] rounded-full pointer-events-none"></div>

        <h1 class="text-3xl md:text-5xl font-black uppercase tracking-tight text-text-primary mb-3 flex justify-center items-center gap-4 relative z-10 drop-shadow-[0_2px_10px_rgba(0,0,0,0.5)]">
            <span class="material-icons text-4xl md:text-5xl text-primary drop-shadow-[0_0_20px_rgba(34,197,94,0.4)]">payments</span>
            {{ __('confirm_payment') }}
        </h1>
        <div class="h-1 w-32 bg-linear-to-r from-transparent via-primary to-transparent mx-auto rounded-full mt-6 opacity-30"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Left Column: Product Info -->
        <div class="md:col-span-2 space-y-8">
            <!-- Product Details -->
            <div class="bg-bg-card rounded-3xl border border-border p-8 relative overflow-hidden group shadow-2xl">
                <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                    <span class="material-icons text-8xl text-primary/30">shopping_bag</span>
                </div>

                <h2 class="text-sm font-black mb-8 flex items-center gap-3 text-text-primary uppercase tracking-[0.2em] border-l-4 border-primary pl-4">
                    {{ __('product_information') }}
                </h2>

                <div class="flex flex-col sm:flex-row gap-8 relative z-10">
                    <div class="w-full sm:w-32 h-32 shrink-0 bg-bg-dark/80 rounded-2xl overflow-hidden border border-border shadow-inner">
                        @if($product->images[0])
                        <img src="{{ url('storage/'.$product->images[0]) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-text-muted bg-bg-dark">
                            <span class="material-icons text-4xl">image</span>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 flex flex-col justify-center">
                        <h3 class="font-black text-xl mb-3 text-text-primary leading-tight tracking-tight group-hover:text-primary transition-colors">{{ $product->title }}</h3>
                        <div class="flex flex-wrap items-center gap-4">
                            <p class="text-[10px] text-primary font-black uppercase tracking-widest flex items-center gap-2 bg-primary/10 px-3 py-1.5 rounded-full border border-primary/20">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
                                {{ $product->category->title ?? 'N/A' }}
                            </p>
                            <span class="text-[10px] text-text-muted font-black uppercase tracking-widest">Mã: #{{ $product->id }}</span>
                        </div>
                        <div class="mt-6 flex items-baseline gap-3">
                            <span class="text-3xl font-black text-primary drop-shadow-[0_0_12px_rgba(34,197,94,0.4)] tracking-tighter">{{ number_format($product->getFinalPrice()) }}đ</span>
                            @if($product->sale_price && $product->sale_price < $product->sell_price)
                                <span class="text-sm text-text-muted line-through font-bold opacity-50">{{ number_format($product->sell_price) }}đ</span>
                                @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wallet Info -->
            <div class="bg-bg-card rounded-3xl border border-border p-8 relative overflow-hidden group shadow-2xl">
                <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                    <span class="material-icons text-8xl text-secondary/30">wallet</span>
                </div>

                <h2 class="text-sm font-black mb-8 flex items-center gap-3 text-text-primary uppercase tracking-[0.2em] border-l-4 border-secondary pl-4">
                    {{ __('wallet_information') }}
                </h2>

                <div class="flex flex-col sm:flex-row items-center justify-between bg-bg-dark/50 backdrop-blur-xl p-8 rounded-2xl border border-border group-hover:border-primary/30 transition-all duration-500">
                    <div class="flex items-center gap-5 mb-6 sm:mb-0">
                        <div class="w-16 h-16 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary border border-secondary/20 shadow-lg">
                            <span class="material-icons text-3xl">account_balance_wallet</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-text-muted font-black uppercase tracking-widest mb-1.5">{{ __('current_balance') }}</p>
                            <p class="font-black text-3xl tracking-tight {{ $wallet->balance < $product->getFinalPrice() ? 'text-pink-500 drop-shadow-[0_0_10px_rgba(236,72,153,0.4)]' : 'text-primary drop-shadow-[0_0_10px_rgba(34,197,94,0.4)]' }}">
                                {{ number_format($wallet->balance) }}đ
                            </p>
                        </div>
                    </div>

                    @if($wallet->balance < $product->getFinalPrice())
                        <a href="{{ route('deposit') }}" class="w-full sm:w-auto px-10 py-4 btn-esport rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl active:scale-95 transition-all text-center">
                            {{ __('deposit_now') }}
                        </a>
                        @else
                        <div class="hidden sm:flex items-center gap-2 text-emerald-400/50 text-[10px] font-black uppercase tracking-widest">
                            <span class="material-icons text-sm">check_circle</span>
                            {{ __('sufficient_balance') }}
                        </div>
                        @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="md:col-span-1">
            <div class="bg-bg-card rounded-4xl border border-border p-8 sticky top-24 shadow-3xl overflow-hidden group">
                <!-- Inner glow -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/5 blur-3xl rounded-full group-hover:bg-primary/10 transition-colors"></div>

                <h2 class="text-xl font-black mb-10 text-text-primary uppercase tracking-tighter flex items-center gap-2">
                    <span class="w-2 h-2 bg-primary rounded-full animate-ping"></span>
                    {{ __('order_summary') }}
                </h2>

                <!-- Coupon Input -->
                <div class="mb-10">
                    <label class="block text-[10px] font-black text-text-muted mb-4 uppercase tracking-[0.2em]">{{ __('coupon_code') }}</label>
                    <div class="flex gap-2">
                        <input type="text" id="coupon_code" class="flex-1 bg-bg-dark/80 border border-border focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl px-5 py-4 text-text-primary text-sm outline-hidden placeholder-text-muted transition-all font-bold tracking-tight shadow-inner" placeholder="PST-888...">
                        <button type="button" id="apply_coupon" class="px-5 py-4 bg-white/5 hover:bg-white/10 text-text-primary border border-border rounded-2xl text-[10px] font-black transition-all uppercase tracking-widest active:scale-95">
                            {{ __('apply') }}
                        </button>
                    </div>
                    <p id="coupon_message" class="text-[10px] font-black mt-3 hidden uppercase tracking-widest"></p>
                </div>

                <div class="space-y-4 pt-8 border-t border-white/5">
                    <div class="flex justify-between text-xs font-bold tracking-tight">
                        <span class="text-text-primary uppercase">{{ __('subtotal') }}</span>
                        <span class="text-text-secondary">{{ number_format($product->getFinalPrice()) }}đ</span>
                    </div>
                    <div class="flex justify-between items-center text-[10px] font-black text-primary bg-primary/5 px-3 py-2 rounded-lg border border-primary/10" id="discount_row" style="display: none;">
                        <span class="uppercase tracking-widest">{{ __('discount') }}</span>
                        <span class="text-sm">-<span id="discount_amount">0</span>đ</span>
                    </div>
                    <div class="flex flex-col gap-1 text-sm font-black pt-8 mt-4 border-t border-white/10">
                        <span class="text-[10px] text-text-muted uppercase tracking-[0.3em] text-center mb-1">{{ __('total_payment') }}</span>
                        <span class="text-primary text-4xl text-center drop-shadow-[0_0_15px_rgba(34,197,94,0.5)] tracking-tighter italic" id="final_total">{{ number_format($product->getFinalPrice()) }}đ</span>
                    </div>
                </div>

                <form action="{{ route('purchase.process') }}" method="POST" class="mt-10">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="coupon_code" id="form_coupon_code">

                    <button type="submit"
                        class="w-full py-6 rounded-2xl font-black text-white shadow-2xl transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-[0.2em] text-sm border-none shadow-primary/20
                            {{ $wallet->balance < $product->getFinalPrice() ? 'bg-bg-dark text-text-muted grayscale cursor-not-allowed border border-border' : 'btn-esport' }}"
                        {{ $wallet->balance < $product->getFinalPrice() ? 'disabled' : '' }}>
                        {{ $wallet->balance < $product->getFinalPrice() ? __('insufficient_balance') : __('pay_now') }}
                    </button>
                </form>

                <p class="text-[9px] text-text-muted font-bold text-center mt-8 leading-loose uppercase tracking-widest max-w-[200px] mx-auto opacity-60">
                    {{ __('payment_policy_agree') }} <a href="{{ route('policy') }}" class="text-primary hover:text-text-primary transition-colors underline underline-offset-4">{{ __('policy_regulations') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const applyBtn = document.getElementById('apply_coupon');
        const couponInput = document.getElementById('coupon_code');
        const couponMessage = document.getElementById('coupon_message');
        const discountRow = document.getElementById('discount_row');
        const discountAmountSpan = document.getElementById('discount_amount');
        const finalTotalSpan = document.getElementById('final_total');
        const formCouponCode = document.getElementById('form_coupon_code');

        // Fix syntax: Blade expressions should be wrapped in strings for JS compatibility
        const originalPrice = parseInt("{{ $product->getFinalPrice() }}");

        applyBtn.addEventListener('click', function() {
            const code = couponInput.value.trim();
            if (!code) return;

            applyBtn.disabled = true;
            applyBtn.innerHTML = '<span class="material-icons animate-spin text-[14px]">refresh</span>';

            fetch('{{ route("purchase.validate-coupon") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code: code,
                        amount: originalPrice
                    })
                })
                .then(response => response.json())
                .then(data => {
                    applyBtn.disabled = false;
                    applyBtn.textContent = "{{ __('apply') }}";

                    couponMessage.textContent = data.message;
                    couponMessage.classList.remove('hidden', 'text-green-500', 'text-pink-500');

                    if (data.valid) {
                        couponMessage.classList.add('text-green-500');
                        discountRow.style.display = 'flex';
                        discountAmountSpan.textContent = new Intl.NumberFormat('vi-VN').format(data.discount);
                        finalTotalSpan.textContent = new Intl.NumberFormat('vi-VN').format(data.final_amount) + 'đ';
                        formCouponCode.value = code;
                    } else {
                        couponMessage.classList.add('text-pink-500');
                        discountRow.style.display = 'none';
                        finalTotalSpan.textContent = new Intl.NumberFormat('vi-VN').format(originalPrice) + 'đ';
                        formCouponCode.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    applyBtn.disabled = false;
                    applyBtn.textContent = "{{ __('apply') }}";
                    couponMessage.textContent = "{{ __('apply_failed') }}";
                    couponMessage.classList.remove('hidden');
                    couponMessage.classList.add('text-pink-500');
                });
        });
    });
</script>
@endsection