<div class="max-w-5xl mx-auto relative z-10 px-4 py-8" x-data="{ validating: false, errorMessage: '' }">
    <div class="mb-12 text-center relative">
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-indigo-500/10 blur-[100px] rounded-full pointer-events-none"></div>
        <h1 class="text-3xl md:text-5xl font-black uppercase tracking-tight text-white mb-3 flex justify-center items-center gap-4 relative z-10">
            <span class="material-icons text-4xl md:text-5xl text-primary drop-shadow-[0_0_10px_rgba(74,222,128,0.5)]">shopping_cart_checkout</span>
            {{ __('confirm_payment') }}
        </h1>
        <div class="h-1 w-32 bg-linear-to-r from-transparent via-primary to-transparent mx-auto rounded-full mt-8"></div>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-pink-500/10 border border-pink-500/20 text-pink-500 rounded-2xl font-black uppercase tracking-widest text-xs flex items-center gap-3">
        <span class="material-icons">error_outline</span>
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left: Items + Shipping -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Cart Items Summary -->
            <div class="glass rounded-3xl border border-white/10 p-8 shadow-2xl">
                <h2 class="text-lg font-black mb-6 flex items-center gap-3 text-white uppercase tracking-wider border-l-4 border-primary pl-4">
                    {{ __('product_information') }}
                    <span class="text-xs text-neutral-500 font-bold normal-case tracking-normal">({{ count($this->items) }} {{ __('cart.items') }})</span>
                </h2>
                <div class="space-y-4">
                    @foreach($this->items as $item)
                    <div class="flex gap-4 items-center">
                        <div class="w-14 h-14 shrink-0 rounded-xl overflow-hidden border border-white/5 bg-neutral-950">
                            @if($item['image'])
                            <img src="{{ url('storage/'.$item['image']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-neutral-700">
                                <span class="material-icons text-sm">image</span>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[9px] text-neutral-500 font-black uppercase tracking-widest">{{ $item['category'] }}</p>
                            <p class="font-black text-white text-sm truncate">{{ $item['title'] }}</p>
                        </div>
                        <span class="text-primary font-black text-sm shrink-0">{{ number_format($item['price']) }}đ</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="glass rounded-3xl border border-white/10 p-8 shadow-2xl">
                <h2 class="text-lg font-black mb-8 flex items-center gap-3 text-white uppercase tracking-wider border-l-4 border-indigo-500 pl-4">
                    {{ __('shipping_information') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('full_name') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">person</span>
                            <input type="text" id="shipping_name" wire:model.live.debounce.300ms="shipping_name" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="John Doe">
                        </div>
                        @error('shipping_name') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('phone_number') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">phone</span>
                            <input type="text" id="shipping_phone" wire:model.live.debounce.300ms="shipping_phone" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="+1 234 567 890">
                        </div>
                        @error('shipping_phone') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('email_address') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">email</span>
                            <input type="email" id="shipping_email" wire:model.live.debounce.300ms="shipping_email" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="johndoe@example.com">
                        </div>
                        @error('shipping_email') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('shipping_address') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">home</span>
                            <input type="text" id="shipping_address" wire:model.live.debounce.300ms="shipping_address" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="123 Main Street">
                        </div>
                        @error('shipping_address') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('city_state') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">location_city</span>
                            <input type="text" id="shipping_city" wire:model.live.debounce.300ms="shipping_city" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="New York / NY">
                        </div>
                        @error('shipping_city') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('postal_code') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">local_post_office</span>
                            <input type="text" id="shipping_postal_code" wire:model.live.debounce.300ms="shipping_postal_code" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="10001">
                        </div>
                        @error('shipping_postal_code') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('country') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">public</span>
                            <select id="shipping_country" wire:model.live="shipping_country" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold appearance-none">
                                <option value="United States" class="bg-neutral-950">United States</option>
                                <option value="United Kingdom" class="bg-neutral-950">United Kingdom</option>
                                <option value="Canada" class="bg-neutral-950">Canada</option>
                                <option value="Germany" class="bg-neutral-950">Germany</option>
                                <option value="France" class="bg-neutral-950">France</option>
                                <option value="Japan" class="bg-neutral-950">Japan</option>
                                <option value="Singapore" class="bg-neutral-950">Singapore</option>
                                <option value="Vietnam" class="bg-neutral-950">Vietnam</option>
                                <option value="Australia" class="bg-neutral-950">Australia</option>
                            </select>
                        </div>
                        @error('shipping_country') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Summary + PayPal -->
        <div class="lg:col-span-1">
            <div class="glass rounded-3xl border border-white/10 p-8 sticky top-24 shadow-3xl overflow-hidden">
                <h2 class="text-xl font-black mb-8 text-white uppercase tracking-tighter">{{ __('order_summary') }}</h2>

                <!-- Coupon -->
                <div class="mb-8">
                    <label class="block text-[10px] font-black text-neutral-400 mb-3 uppercase tracking-widest">{{ __('coupon_code') }}</label>
                    <div class="flex flex-col gap-2">
                        <input type="text" wire:model.defer="couponCode"
                            class="flex-1 bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-primary/20 rounded-xl px-4 py-3 text-neutral-200 text-sm outline-hidden placeholder-neutral-700 transition-all font-bold"
                            placeholder="{{ __('cart.coupon_placeholder') }}"
                            @if($couponValid) disabled @endif>
                        @if($couponValid)
                        <button type="button" wire:click="removeCoupon" class="shrink-0 whitespace-nowrap px-4 py-3 bg-pink-500 text-white rounded-xl text-xs font-black uppercase tracking-widest">
                            {{ __('cart.remove') }}
                        </button>
                        @else
                        <button type="button" wire:click="applyCoupon" wire:loading.attr="disabled" wire:target="applyCoupon"
                            class="shrink-0 whitespace-nowrap px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all disabled:opacity-50">
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
                <div class="space-y-4 pt-6 border-t border-white/5">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-neutral-500 uppercase tracking-widest">{{ __('subtotal') }}</span>
                        <span class="font-black text-neutral-200">{{ number_format($this->subtotal) }}đ</span>
                    </div>
                    @if($discount > 0)
                    <div class="flex justify-between text-xs font-black text-emerald-400 uppercase tracking-widest">
                        <span>{{ __('discount') }}</span>
                        <span>-{{ number_format($discount) }}đ</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center text-sm font-black pt-5 border-t border-white/5">
                        <span class="text-neutral-400 uppercase tracking-widest">{{ __('total_payment') }}</span>
                        <div class="text-right">
                            <span class="text-primary text-2xl drop-shadow-[0_0_10px_rgba(74,222,128,0.4)] block">{{ number_format($this->finalAmount) }}đ</span>
                            <span class="text-[10px] text-neutral-500 block font-normal tracking-wide mt-0.5">≈ ${{ $this->finalAmountUSD }} USD</span>
                        </div>
                    </div>
                </div>

                <!-- PayPal -->
                <div class="mt-8 pt-8 border-t border-white/5">
                    <h3 class="text-[10px] font-black text-neutral-400 mb-4 uppercase tracking-widest">{{ __('payment_method') }}</h3>
                    <div class="space-y-4">
                        @if($paymentConfig['paypal_enabled'] ?? false)
                        <label class="block cursor-pointer">
                            <input type="radio" class="hidden" wire:model.live="paymentMethod" value="paypal">
                            <div class="p-4 rounded-2xl border transition-all {{ $paymentMethod === 'paypal' ? 'bg-indigo-950/30 border-indigo-400/40' : 'bg-indigo-950/10 border-indigo-500/10' }}">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="material-icons text-indigo-400 text-lg">payment</span>
                                    <span class="font-black text-xs text-white uppercase tracking-wider">PayPal / Credit Card</span>
                                </div>
                                <p class="text-[10px] text-neutral-500 leading-relaxed font-bold uppercase tracking-wider">{{ __('paypal_payment_desc') }}</p>
                            </div>
                        </label>
                        @endif

                        @if($paymentConfig['vietqr_enabled'] ?? false)
                        <label class="block cursor-pointer">
                            <input type="radio" class="hidden" wire:model.live="paymentMethod" value="vietqr">
                            <div class="p-4 rounded-2xl border transition-all {{ $paymentMethod === 'vietqr' ? 'bg-emerald-950/30 border-emerald-400/40' : 'bg-emerald-950/10 border-emerald-500/10' }}">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="material-icons text-emerald-400 text-lg">qr_code_2</span>
                                    <span class="font-black text-xs text-white uppercase tracking-wider">VietQR / Bank Transfer</span>
                                </div>
                                <p class="text-[10px] text-neutral-500 leading-relaxed font-bold uppercase tracking-wider">{{ __('vietqr_payment_desc') }}</p>
                            </div>
                        </label>
                        @endif

                        <label class="block cursor-pointer">
                            <input type="radio" class="hidden" wire:model.live="paymentMethod" value="cod">
                            <div class="p-4 rounded-2xl border transition-all {{ $paymentMethod === 'cod' ? 'bg-amber-950/30 border-amber-400/40' : 'bg-amber-950/10 border-amber-500/10' }}">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="material-icons text-amber-400 text-lg">local_shipping</span>
                                    <span class="font-black text-xs text-white uppercase tracking-wider">COD / Cash on Delivery</span>
                                </div>
                                <p class="text-[10px] text-neutral-500 leading-relaxed font-bold uppercase tracking-wider">{{ __('cod_payment_desc') }}</p>
                            </div>
                        </label>
                    </div>

                    @if($paymentMethod === 'paypal')
                        @if($paymentConfig['paypal_enabled'] ?? false)
                        <div class="p-4 bg-indigo-950/20 border border-indigo-500/10 rounded-2xl my-6">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="material-icons text-indigo-400 text-lg">payment</span>
                                <span class="font-black text-xs text-white uppercase tracking-wider">PayPal / Credit Card</span>
                            </div>
                            <p class="text-[10px] text-neutral-500 leading-relaxed font-bold uppercase tracking-wider">{{ __('paypal_payment_desc') }}</p>
                        </div>

                        <div x-show="errorMessage" class="mb-4 p-3 bg-pink-500/10 border border-pink-500/20 text-pink-500 rounded-xl font-black uppercase tracking-widest text-[9px] flex items-center gap-2" style="display: none;">
                            <span class="material-icons text-xs">warning</span>
                            <span x-text="errorMessage"></span>
                        </div>

                        <div x-show="validating" class="mb-4 p-4 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-2xl font-black uppercase tracking-widest text-xs flex items-center justify-center gap-2" style="display: none;">
                            <span class="material-icons animate-spin text-sm">refresh</span>
                            PROCESSING PAYMENT...
                        </div>

                        <div wire:ignore id="paypal-button-container" class="relative z-10" style="min-height: 150px;"></div>
                        <p id="paypal-debug-message" class="hidden mt-3 text-[10px] font-black uppercase tracking-widest text-amber-300">
                            PayPal button could not be rendered. Check PayPal client ID or SDK loading.
                        </p>
                        @else
                        <div class="p-4 bg-white/5 border border-white/10 rounded-2xl text-xs font-black uppercase tracking-widest text-neutral-400">
                            {{ __('paypal_currently_unavailable') }}
                        </div>
                        @endif
                    @elseif($paymentMethod === 'vietqr')
                    <div class="my-6 p-5 bg-emerald-950/20 border border-emerald-500/10 rounded-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="material-icons text-emerald-400 text-xl">qr_code_2</span>
                            <span class="font-black text-xs text-white uppercase tracking-wider">VietQR</span>
                        </div>
                        @if($vietQrOrderCreated && $vietQrPaymentReference)
                        <p class="text-[10px] text-emerald-300 font-black uppercase tracking-widest mb-4">{{ __('vietqr_order_created') }}</p>
                        <button
                            type="button"
                            wire:click="openVietQrModal"
                            class="w-full px-4 py-3 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all"
                        >
                            {{ __('vietqr_open_qr_modal') }}
                        </button>
                        @else
                        <p class="text-[10px] text-neutral-400 font-bold uppercase tracking-widest mb-4">{{ __('vietqr_generate_before_transfer') }}</p>
                        <button
                            type="button"
                            wire:click="processVietQrOrder"
                            wire:loading.attr="disabled"
                            wire:target="processVietQrOrder"
                            class="mt-5 w-full px-4 py-3 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="processVietQrOrder">{{ __('vietqr_generate_order') }}</span>
                            <span wire:loading wire:target="processVietQrOrder">{{ __('cart.processing') }}</span>
                        </button>
                        @endif
                    </div>
                    @elseif($paymentMethod === 'cod')
                    <div class="my-6 p-5 bg-amber-950/20 border border-amber-500/10 rounded-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="material-icons text-amber-400 text-xl">local_shipping</span>
                            <span class="font-black text-xs text-white uppercase tracking-wider">Cash on Delivery</span>
                        </div>
                        <div class="space-y-3 text-xs text-neutral-300">
                            <p>{{ __('cod_payment_desc') }}</p>
                            <p><span class="text-neutral-500 uppercase tracking-widest">{{ __('total_payment') }}:</span> <span class="font-bold text-primary">{{ number_format($this->finalAmount) }}đ</span></p>
                            <p><span class="text-neutral-500 uppercase tracking-widest">{{ __('shipping_information') }}:</span> <span class="font-bold text-white">{{ $shipping_address ?: '—' }}</span></p>
                        </div>
                        <button
                            type="button"
                            wire:click="processCodOrder"
                            wire:loading.attr="disabled"
                            wire:target="processCodOrder"
                            class="mt-5 w-full px-4 py-3 bg-amber-500 hover:bg-amber-400 text-neutral-950 rounded-xl text-xs font-black uppercase tracking-widest transition-all disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="processCodOrder">{{ __('cod_confirm_order') }}</span>
                            <span wire:loading wire:target="processCodOrder">{{ __('cart.processing') }}</span>
                        </button>
                    </div>
                    @endif
                </div>

                <p class="text-[10px] text-neutral-600 font-bold text-center mt-6 leading-relaxed uppercase tracking-widest">
                    {{ __('payment_policy_agree') }} <a href="{{ route('policy') }}" class="text-primary hover:underline">{{ __('policy_regulations') }}</a>.
                </p>
            </div>
        </div>
    </div>

    @if($paymentMethod === 'vietqr' && $vietQrOrderCreated && $vietQrPaymentReference && $showVietQrModal)
    <div wire:poll.4s="checkVietQrPaymentStatus" class="fixed inset-0 z-[120] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeVietQrModal"></div>
        <div class="relative w-full max-w-md bg-neutral-950 border border-emerald-500/20 rounded-3xl p-6 shadow-2xl">
            <button
                type="button"
                wire:click="closeVietQrModal"
                class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/5 hover:bg-white/10 text-white flex items-center justify-center"
            >
                <span class="material-icons text-sm">close</span>
            </button>

            <div class="flex items-center gap-3 mb-5">
                <span class="material-icons text-emerald-400 text-2xl">qr_code_2</span>
                <div>
                    <h3 class="text-white text-sm font-black uppercase tracking-widest">VietQR</h3>
                    <p class="text-emerald-300 text-[10px] font-black uppercase tracking-widest">{{ __('vietqr_auto_confirm_note') }}</p>
                </div>
            </div>

            <div class="flex justify-center mb-5">
                <div class="bg-white p-3 rounded-2xl">
                    <img
                        alt="VietQR"
                        class="w-56 h-56 rounded-xl"
                        src="https://api.vietqr.io/image/{{ $paymentConfig['bank_bin'] }}-{{ $paymentConfig['bank_number'] }}-compact2.png?amount={{ (int) $this->vietQrAmount }}&addInfo={{ urlencode($vietQrPaymentReference) }}&accountName={{ urlencode($paymentConfig['bank_account_name'] ?? '') }}"
                    >
                </div>
            </div>

            <div class="space-y-2 text-xs">
                <p class="text-neutral-300"><span class="text-neutral-500 uppercase tracking-widest">{{ __('bank') }}:</span> <span class="font-bold text-white">{{ $paymentConfig['bank_name'] ?? '—' }}</span></p>
                <p class="text-neutral-300"><span class="text-neutral-500 uppercase tracking-widest">{{ __('account_holder') }}:</span> <span class="font-bold text-white">{{ $paymentConfig['bank_account_name'] ?? '—' }}</span></p>
                <p class="text-neutral-300"><span class="text-neutral-500 uppercase tracking-widest">{{ __('account_number') }}:</span> <span class="font-bold text-white">{{ $paymentConfig['bank_number'] ?? '—' }}</span></p>
                <p class="text-neutral-300"><span class="text-neutral-500 uppercase tracking-widest">{{ __('total_payment') }}:</span> <span class="font-bold text-primary">{{ number_format($this->vietQrAmount) }}đ</span></p>
                <p class="text-neutral-300"><span class="text-neutral-500 uppercase tracking-widest">{{ __('transfer_reference') }}:</span> <span class="font-bold text-white">{{ $vietQrPaymentReference }}</span></p>
            </div>
        </div>
    </div>
    @endif

    <!-- PayPal SDK -->
    @if($paymentConfig['paypal_enabled'] ?? false)
    <script src="https://www.paypal.com/sdk/js?client-id={{ urlencode($paymentConfig['paypal_client_id'] ?? 'sb') }}&currency={{ urlencode($paymentConfig['paypal_currency'] ?? 'USD') }}&disable-funding=card"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            if (typeof paypal === 'undefined') {
                return;
            }

            function renderPayPalButtons() {
                const container = document.getElementById('paypal-button-container');
                const debugMessage = document.getElementById('paypal-debug-message');

                if (!container) {
                    return;
                }

                container.innerHTML = '';
                container.dataset.rendered = 'false';
                if (debugMessage) {
                    debugMessage.classList.add('hidden');
                }

                function getShippingErrors() {
                    const fields = ['shipping_name','shipping_phone','shipping_email','shipping_address','shipping_city','shipping_postal_code','shipping_country'];
                    for (const id of fields) {
                        const el = document.getElementById(id);
                        if (!el || !el.value.trim()) return "{{ __('validate_shipping_details') }}";
                    }
                    return null;
                }

                paypal.Buttons({
                    onClick: function(data, actions) {
                        const err = getShippingErrors();
                        if (err) {
                            document.querySelector('[x-data]').__x.$data.errorMessage = err;
                            document.getElementById('shipping_name').scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return actions.reject();
                        }
                        document.querySelector('[x-data]').__x.$data.errorMessage = '';
                        return actions.resolve();
                    },
                    createOrder: function(data, actions) {
                        const usd = parseFloat(@this.get('finalAmountUSD'));
                        return actions.order.create({
                            purchase_units: [{ amount: { currency_code: '{{ $paymentConfig['paypal_currency'] ?? 'USD' }}', value: usd.toFixed(2) } }]
                        });
                    },
                    onApprove: function(data, actions) {
                        const alpine = document.querySelector('[x-data]').__x.$data;
                        alpine.validating = true;
                        alpine.errorMessage = '';
                        return actions.order.capture().then(function(details) {
                            const fields = ['shipping_name','shipping_phone','shipping_email','shipping_address','shipping_city','shipping_postal_code','shipping_country'];
                            fields.forEach(id => @this.set(id, document.getElementById(id).value.trim()));
                            @this.call('processPayPalPayment', details.id);
                        });
                    },
                    onError: function(err) {
                        console.error('PayPal error:', err);
                        const alpine = document.querySelector('[x-data]').__x.$data;
                        alpine.errorMessage = 'An error occurred during PayPal Checkout. Please try again.';
                        alpine.validating = false;
                    }
                }).render('#paypal-button-container').then(function () {
                    container.dataset.rendered = 'true';
                }).catch(function (err) {
                    console.error('PayPal render error:', err);
                    if (debugMessage) {
                        debugMessage.classList.remove('hidden');
                    }
                });
            }

            renderPayPalButtons();

            document.addEventListener('livewire:init', () => {
                Livewire.on('paypal-method-selected', () => {
                    setTimeout(renderPayPalButtons, 100);
                });
            });
        });
    </script>
    @endif
</div>
