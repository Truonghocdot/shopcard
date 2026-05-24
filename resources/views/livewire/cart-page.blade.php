<div class="max-w-5xl mx-auto px-4 py-10 relative z-10">

    <div class="mb-10 text-center">
        <h1 class="text-3xl md:text-5xl font-black uppercase tracking-tight text-white mb-3 flex justify-center items-center gap-4">
            <span class="material-icons text-4xl text-primary drop-shadow-[0_0_10px_rgba(74,222,128,0.5)]">shopping_cart</span>
            {{ __('cart.title') }}
        </h1>
        <div class="h-1 w-32 bg-linear-to-r from-transparent via-primary to-transparent mx-auto rounded-full mt-6"></div>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-pink-500/10 border border-pink-500/20 text-pink-500 rounded-2xl font-black uppercase tracking-widest text-xs flex items-center gap-3">
        <span class="material-icons">error_outline</span>
        {{ session('error') }}
    </div>
    @endif

    @if($this->count === 0)
    <div class="glass rounded-3xl border border-white/10 p-16 text-center shadow-2xl">
        <span class="material-icons text-7xl text-neutral-700 mb-6 block">shopping_cart_off</span>
        <p class="text-neutral-400 font-black uppercase tracking-widest text-sm mb-8">{{ __('cart.empty') }}</p>
        <a href="{{ route('products.index') }}" class="btn-esport py-4 px-10 rounded-2xl font-black uppercase tracking-widest text-sm inline-flex items-center gap-3">
            <span class="material-icons">storefront</span>
            {{ __('cart.browse_products') }}
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($this->items as $item)
            <div class="glass rounded-2xl border border-white/10 p-5 flex gap-5 items-center shadow-xl">
                <!-- Image -->
                <div class="w-20 h-20 shrink-0 rounded-xl overflow-hidden border border-white/5 bg-neutral-950">
                    @if($item['image'])
                    <img src="{{ url('storage/'.$item['image']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-neutral-700">
                        <span class="material-icons">image</span>
                    </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <p class="text-[9px] text-neutral-500 font-black uppercase tracking-widest mb-1">{{ $item['category'] }}</p>
                    <h3 class="font-black text-white text-sm leading-tight truncate">{{ $item['title'] }}</h3>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-primary font-black text-lg">{{ number_format($item['price']) }}đ</span>
                        @if(isset($item['sell_price']) && $item['sell_price'] > $item['price'])
                        <span class="text-neutral-600 line-through text-xs font-bold">{{ number_format($item['sell_price']) }}đ</span>
                        @endif
                    </div>
                </div>

                <!-- Remove -->
                <button
                    wire:click="removeItem({{ $item['product_id'] }})"
                    wire:loading.attr="disabled"
                    wire:target="removeItem({{ $item['product_id'] }})"
                    class="shrink-0 w-9 h-9 rounded-xl bg-pink-500/10 hover:bg-pink-500/20 text-pink-500 flex items-center justify-center transition-colors">
                    <span class="material-icons text-sm">delete</span>
                </button>
            </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="lg:col-span-1">
            <div class="glass rounded-3xl border border-white/10 p-8 sticky top-24 shadow-2xl">
                <h2 class="text-lg font-black text-white uppercase tracking-tighter mb-6">{{ __('order_summary') }}</h2>

                <!-- Coupon -->
                <div class="mb-6">
                    <label class="block text-[10px] font-black text-neutral-400 mb-2 uppercase tracking-widest">{{ __('coupon_code') }}</label>
                    <div class="flex flex-col gap-2">
                        <input
                            type="text"
                            wire:model.defer="couponCode"
                            class="flex-1 bg-neutral-950/50 border border-white/10 focus:border-primary rounded-xl px-4 py-3 text-neutral-200 text-sm outline-hidden placeholder-neutral-700 font-bold transition-all"
                            placeholder="{{ __('cart.coupon_placeholder') }}"
                            @if($couponValid) disabled @endif>
                        @if($couponValid)
                        <button wire:click="removeCoupon" class="shrink-0 px-4 py-3 bg-pink-500 text-white rounded-xl text-xs font-black uppercase tracking-widest">
                            {{ __('cart.remove') }}
                        </button>
                        @else
                        <button wire:click="applyCoupon" wire:loading.attr="disabled" wire:target="applyCoupon"
                            class="shrink-0 px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all disabled:opacity-50">
                            <span wire:loading.remove wire:target="applyCoupon">{{ __('apply') }}</span>
                            <span wire:loading wire:target="applyCoupon"><span class="material-icons animate-spin text-sm">refresh</span></span>
                        </button>
                        @endif
                    </div>
                    @if($couponMessage)
                    <p class="text-[10px] font-black mt-2 uppercase tracking-widest {{ $couponValid ? 'text-emerald-400' : 'text-pink-500' }}">
                        {{ $couponMessage }}
                    </p>
                    @endif
                </div>

                <!-- Totals -->
                <div class="space-y-3 pt-5 border-t border-white/5">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-neutral-500 uppercase tracking-widest">{{ __('subtotal') }}</span>
                        <span class="text-neutral-200 font-black">{{ number_format($this->subtotal) }}đ</span>
                    </div>
                    @if($discount > 0)
                    <div class="flex justify-between text-xs font-black text-emerald-400 uppercase tracking-widest">
                        <span>{{ __('discount') }}</span>
                        <span>-{{ number_format($discount) }}đ</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center text-sm font-black pt-4 border-t border-white/5">
                        <span class="text-neutral-400 uppercase tracking-widest">{{ __('total_payment') }}</span>
                        <div class="text-right">
                            <span class="text-primary text-2xl drop-shadow-[0_0_10px_rgba(74,222,128,0.4)] block">{{ number_format($this->finalAmount) }}đ</span>
                            <span class="text-[10px] text-neutral-500 font-normal">≈ ${{ $this->finalAmountUSD }} USD</span>
                        </div>
                    </div>
                </div>

                <!-- Checkout Button -->
                <button
                    wire:click="proceedToCheckout"
                    wire:loading.attr="disabled"
                    wire:target="proceedToCheckout"
                    class="mt-8 w-full btn-esport py-4 rounded-2xl font-black uppercase tracking-widest text-sm flex items-center justify-center gap-3 border-none shadow-primary/30 active:scale-95 transition-all disabled:opacity-60">
                    <span wire:loading.remove wire:target="proceedToCheckout" class="material-icons">arrow_forward</span>
                    <span wire:loading wire:target="proceedToCheckout" class="material-icons animate-spin">refresh</span>
                    <span wire:loading.remove wire:target="proceedToCheckout">{{ __('cart.proceed_checkout') }}</span>
                    <span wire:loading wire:target="proceedToCheckout">{{ __('cart.processing') }}</span>
                </button>

                <a href="{{ route('products.index') }}" class="mt-4 w-full flex items-center justify-center gap-2 text-neutral-500 hover:text-primary text-[10px] font-black uppercase tracking-widest transition-colors">
                    <span class="material-icons text-sm">arrow_back</span>
                    {{ __('cart.continue_shopping') }}
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
