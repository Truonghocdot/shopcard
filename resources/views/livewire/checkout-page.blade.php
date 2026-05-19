<div class="max-w-5xl mx-auto relative z-10 px-4 py-8" x-data="{ validating: false, errorMessage: '' }">
    <div class="mb-12 text-center relative">
        <!-- Decorative background glow -->
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-indigo-500/10 blur-[100px] rounded-full pointer-events-none"></div>

        <h1 class="text-3xl md:text-5xl font-black uppercase tracking-tight text-white mb-3 flex justify-center items-center gap-4 relative z-10">
            <span class="material-icons text-4xl md:text-5xl text-primary drop-shadow-[0_0_10px_rgba(74,222,128,0.5)]">shopping_cart</span>
            {{ __('confirm_payment') }}
        </h1>
        <div class="h-1 w-32 bg-linear-to-r from-transparent via-primary to-transparent mx-auto rounded-full mt-8"></div>
    </div>

    @if (session('error'))
    <div class="mb-6 p-4 bg-pink-500/10 border border-pink-500/20 text-pink-500 rounded-2xl font-black uppercase tracking-widest text-xs flex items-center gap-3">
        <span class="material-icons">error_outline</span>
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Product Info & Shipping Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Details -->
            <div class="glass rounded-3xl border border-white/10 p-8 relative overflow-hidden group shadow-2xl">
                <h2 class="text-lg font-black mb-8 flex items-center gap-3 text-white uppercase tracking-wider border-l-4 border-primary pl-4">
                    {{ __('product_information') }}
                </h2>

                <div class="flex flex-col sm:flex-row gap-6">
                    <div class="w-full sm:w-28 h-28 shrink-0 bg-neutral-950/50 rounded-xl overflow-hidden border border-white/5 relative">
                        @if($product->images && count($product->images) > 0 && $product->images[0])
                        <img src="{{ url('storage/'.$product->images[0]) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-neutral-800 bg-neutral-900">
                            <span class="material-icons text-4xl">image</span>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 flex flex-col justify-center">
                        <h3 class="font-black text-xl mb-2 text-white leading-tight">{{ $product->title }}</h3>
                        <p class="text-[10px] text-neutral-400 mb-4 font-black uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                            {{ __('category') }}: {{ $product->category->title ?? 'N/A' }}
                        </p>
                        <div class="flex items-center gap-3">
                            <span class="text-2xl font-black text-primary drop-shadow-[0_0_8px_rgba(74,222,128,0.4)]">{{ number_format($this->originalPrice) }}đ</span>
                            @if($product->sale_price && $product->sale_price < $product->sell_price)
                                <span class="text-xs text-neutral-600 line-through font-bold">{{ number_format($product->sell_price) }}đ</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Info Form -->
            <div class="glass rounded-3xl border border-white/10 p-8 relative overflow-hidden group shadow-2xl">
                <h2 class="text-lg font-black mb-8 flex items-center gap-3 text-white uppercase tracking-wider border-l-4 border-indigo-500 pl-4">
                    {{ __('shipping_information') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('full_name') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">person</span>
                            <input type="text" id="shipping_name" wire:model.defer="shipping_name" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="John Doe">
                        </div>
                        @error('shipping_name') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('phone_number') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">phone</span>
                            <input type="text" id="shipping_phone" wire:model.defer="shipping_phone" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="+1 234 567 890">
                        </div>
                        @error('shipping_phone') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('email_address') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">email</span>
                            <input type="email" id="shipping_email" wire:model.defer="shipping_email" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="johndoe@example.com">
                        </div>
                        @error('shipping_email') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Shipping Address -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('shipping_address') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">home</span>
                            <input type="text" id="shipping_address" wire:model.defer="shipping_address" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="123 Main Street, Apt 4B">
                        </div>
                        @error('shipping_address') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- City / State -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('city_state') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">location_city</span>
                            <input type="text" id="shipping_city" wire:model.defer="shipping_city" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="New York / NY">
                        </div>
                        @error('shipping_city') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Postal Code -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('postal_code') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">local_post_office</span>
                            <input type="text" id="shipping_postal_code" wire:model.defer="shipping_postal_code" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold" placeholder="10001">
                        </div>
                        @error('shipping_postal_code') <p class="text-[9px] font-black text-pink-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Country -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ __('country') }} <span class="text-pink-500">*</span></label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-sm">public</span>
                            <select id="shipping_country" wire:model.defer="shipping_country" class="w-full bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm outline-hidden transition-all font-bold appearance-none">
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

        <!-- Right Column: Order Summary & PayPal Checkout -->
        <div class="lg:col-span-1">
            <div class="glass rounded-3xl border border-white/10 p-8 sticky top-24 shadow-3xl overflow-hidden">
                <h2 class="text-xl font-black mb-8 text-white uppercase tracking-tighter">{{ __('order_summary') }}</h2>

                <!-- Coupon Input -->
                <div class="mb-8">
                    <label class="block text-[10px] font-black text-neutral-400 mb-3 uppercase tracking-widest">{{ __('coupon_code') }}</label>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            wire:model.defer="couponCode"
                            class="flex-1 bg-neutral-950/50 border border-white/10 focus:border-primary focus:ring-primary/20 rounded-xl px-4 py-3 text-neutral-200 text-sm outline-hidden placeholder-neutral-700 transition-all font-bold"
                            placeholder="NHẬP MÃ..."
                            @if($couponValid)
                            disabled
                            @endif>
                        @if($couponValid)
                        <button
                            type="button"
                            wire:click="removeCoupon"
                            class="shrink-0 whitespace-nowrap px-4 py-3 bg-pink-500 text-white rounded-xl text-xs font-black transition-all shadow-lg shadow-pink-500/20 uppercase tracking-widest">
                            XÓA
                        </button>
                        @else
                        <button
                            type="button"
                            wire:click="applyCoupon"
                            wire:loading.attr="disabled"
                            wire:target="applyCoupon"
                            class="shrink-0 whitespace-nowrap px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-black transition-all uppercase tracking-widest disabled:opacity-50">
                            <span wire:loading.remove wire:target="applyCoupon">{{ __('apply') }}</span>
                            <span wire:loading wire:target="applyCoupon" class="flex items-center gap-1">
                                <span class="material-icons animate-spin text-sm">refresh</span>
                            </span>
                        </button>
                        @endif
                    </div>

                    @if($couponMessage)
                    <p class="text-[10px] font-black mt-2 uppercase tracking-widest {{ $couponValid ? 'text-emerald-400' : 'text-pink-500' }}">
                        {{ $couponMessage }}
                    </p>
                    @endif
                </div>

                <div class="space-y-4 pt-6 border-t border-white/5">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-neutral-500 uppercase tracking-widest">{{ __('subtotal') }}</span>
                        <span class="font-black text-neutral-200">{{ number_format($this->originalPrice) }}đ</span>
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

                <!-- PayPal Button Container -->
                <div class="mt-8 pt-8 border-t border-white/5">
                    <h3 class="text-[10px] font-black text-neutral-400 mb-4 uppercase tracking-widest">{{ __('payment_method') }}</h3>
                    <div class="p-4 bg-indigo-950/20 border border-indigo-500/10 rounded-2xl mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="material-icons text-indigo-400 text-lg">payment</span>
                            <span class="font-black text-xs text-white uppercase tracking-wider">PayPal / Credit Card</span>
                        </div>
                        <p class="text-[10px] text-neutral-500 leading-relaxed font-bold uppercase tracking-wider">
                            {{ __('paypal_payment_desc') }}
                        </p>
                    </div>

                    <!-- Client-Side Validation Message -->
                    <div x-show="errorMessage" class="mb-4 p-3 bg-pink-500/10 border border-pink-500/20 text-pink-500 rounded-xl font-black uppercase tracking-widest text-[9px] flex items-center gap-2" style="display: none;">
                        <span class="material-icons text-xs">warning</span>
                        <span x-text="errorMessage"></span>
                    </div>

                    <!-- Loader overlay while placing order -->
                    <div x-show="validating" class="mb-4 p-4 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-2xl font-black uppercase tracking-widest text-xs flex items-center justify-center gap-2" style="display: none;">
                        <span class="material-icons animate-spin text-sm">refresh</span>
                        PROCESSING PAYMENT AND PLACING ORDER...
                    </div>

                    <!-- PayPal buttons element -->
                    <div id="paypal-button-container" class="relative z-10" style="min-height: 150px;"></div>
                </div>

                <p class="text-[10px] text-neutral-600 font-bold text-center mt-6 leading-relaxed uppercase tracking-widest">
                    {{ __('payment_policy_agree') }} <a href="{{ route('policy') }}" class="text-primary hover:underline">{{ __('policy_regulations') }}</a>.
                </p>
            </div>
        </div>
    </div>

    <!-- PayPal SDK Script -->
    <script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD&disable-funding=card"></script>

    <script>
        document.addEventListener('livewire:load', function () {
            // Function to check if shipping info is completed
            function getShippingErrors() {
                const name = document.getElementById('shipping_name').value.trim();
                const phone = document.getElementById('shipping_phone').value.trim();
                const email = document.getElementById('shipping_email').value.trim();
                const address = document.getElementById('shipping_address').value.trim();
                const city = document.getElementById('shipping_city').value.trim();
                const postal = document.getElementById('shipping_postal_code').value.trim();
                const country = document.getElementById('shipping_country').value.trim();

                if (!name || !phone || !email || !address || !city || !postal || !country) {
                    return "{{ __('validate_shipping_details') }}";
                }
                return null;
            }

            paypal.Buttons({
                onClick: function(data, actions) {
                    const error = getShippingErrors();
                    if (error) {
                        const AlpineData = document.querySelector('[x-data]').__x.$data;
                        AlpineData.errorMessage = error;
                        
                        // Scroll to form if invalid
                        document.getElementById('shipping_name').scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        return actions.reject();
                    } else {
                        const AlpineData = document.querySelector('[x-data]').__x.$data;
                        AlpineData.errorMessage = '';
                        return actions.resolve();
                    }
                },
                createOrder: function(data, actions) {
                    // Get latest USD total amount from Livewire dynamically
                    const finalAmountUSD = parseFloat(@this.get('finalAmountUSD'));
                    
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                currency_code: 'USD',
                                value: finalAmountUSD.toFixed(2)
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    const AlpineData = document.querySelector('[x-data]').__x.$data;
                    AlpineData.validating = true;
                    AlpineData.errorMessage = '';

                    return actions.order.capture().then(function(details) {
                        // Capture properties from inputs directly to ensure synchronization
                        const name = document.getElementById('shipping_name').value.trim();
                        const phone = document.getElementById('shipping_phone').value.trim();
                        const email = document.getElementById('shipping_email').value.trim();
                        const address = document.getElementById('shipping_address').value.trim();
                        const city = document.getElementById('shipping_city').value.trim();
                        const postal = document.getElementById('shipping_postal_code').value.trim();
                        const country = document.getElementById('shipping_country').value.trim();

                        // Sync to Livewire component
                        @this.set('shipping_name', name);
                        @this.set('shipping_phone', phone);
                        @this.set('shipping_email', email);
                        @this.set('shipping_address', address);
                        @this.set('shipping_city', city);
                        @this.set('shipping_postal_code', postal);
                        @this.set('shipping_country', country);

                        // Process the successful PayPal payment backend-side
                        @this.call('processPayPalPayment', details.id);
                    });
                },
                onError: function(err) {
                    console.error('PayPal Checkout error:', err);
                    const AlpineData = document.querySelector('[x-data]').__x.$data;
                    AlpineData.errorMessage = 'An error occurred during PayPal Checkout. Please try again.';
                    AlpineData.validating = false;
                }
            }).render('#paypal-button-container');
        });
    </script>
</div>