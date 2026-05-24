@extends('layouts.app')

@section('title', __('order_success_title') . ' - Rabby TCG')

@section('content')
@php
    $notes = json_decode($order->notes, true);
    $shipping = $notes['shipping_info'] ?? null;
    $paypalTx = $notes['paypal_transaction_id'] ?? null;
@endphp

<div class="max-w-3xl mx-auto relative z-10 px-4 py-12">
    <div class="glass rounded-4xl border border-white/10 p-8 md:p-12 shadow-3xl relative overflow-hidden group">
        <!-- Decorative glowing ambient light -->
        <div class="absolute -top-24 -left-24 w-80 h-80 bg-primary/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <!-- Success Header -->
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-primary/10 border border-primary/30 rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_20px_rgba(74,222,128,0.2)]">
                <span class="material-icons text-5xl text-primary animate-pulse">check_circle</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-black uppercase tracking-tight text-white mb-2 italic">{{ __('order_success_title') }}</h1>
            <p class="text-neutral-400 text-sm font-bold uppercase tracking-wider">{{ __('thank_you_purchase') }}</p>
            <div class="h-0.5 w-24 bg-linear-to-r from-transparent via-primary to-transparent mx-auto mt-6"></div>
        </div>

        <!-- Order Summary Details -->
        <div class="bg-neutral-950/40 backdrop-blur-md rounded-3xl p-6 md:p-8 border border-white/5 space-y-8 mb-10">
            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 pb-6 border-b border-white/5">
                <div>
                    <p class="text-[9px] text-neutral-500 uppercase tracking-widest font-black mb-1">ORDER NUMBER</p>
                    <p class="font-mono font-black text-xl text-primary drop-shadow-[0_0_8px_rgba(74,222,128,0.3)]">#{{ $order->order_number }}</p>
                </div>
                <div class="md:text-right">
                    <p class="text-[9px] text-neutral-500 uppercase tracking-widest font-black mb-1">DATE OF PURCHASE</p>
                    <p class="text-sm font-bold text-neutral-300">{{ $order->created_at->format('M d, Y - H:i') }}</p>
                </div>
            </div>

            <!-- Product details -->
            <div class="flex gap-6 items-center">
                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-neutral-900 border border-white/10 shrink-0">
                    @if(isset($order->product->images[0]))
                    <img src="{{ url('storage/'.$order->product->images[0]) }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-neutral-600">
                        <span class="material-icons">image</span>
                    </div>
                    @endif
                </div>
                <div>
                    <h3 class="font-black text-lg text-white leading-tight mb-1">{{ $order->product->title ?? 'Card Product' }}</h3>
                    <p class="text-[10px] text-neutral-500 font-bold uppercase tracking-widest">{{ $order->product->category->title ?? 'Collectible' }}</p>
                </div>
            </div>

            <!-- Shipping Details if Physical Order -->
            @if($shipping)
            <div class="bg-white/5 rounded-2xl border border-white/5 p-6 space-y-4">
                <h4 class="font-black text-xs text-white uppercase tracking-widest flex items-center gap-2 border-b border-white/5 pb-3">
                    <span class="material-icons text-primary text-sm">local_shipping</span>
                    {{ __('shipping_information') }}
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('full_name') }}</span>
                        <span class="font-bold text-neutral-200">{{ $shipping['name'] }}</span>
                    </div>
                    <div>
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('phone_number') }}</span>
                        <span class="font-bold text-neutral-200">{{ $shipping['phone'] }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('email_address') }}</span>
                        <span class="font-bold text-neutral-200">{{ $shipping['email'] }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('shipping_address') }}</span>
                        <span class="font-bold text-neutral-200">{{ $shipping['address'] }}, {{ $shipping['city'] }}, {{ $shipping['postal_code'] }}, {{ $shipping['country'] }}</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white/5 rounded-2xl border border-white/5 p-6 space-y-4">
                <h4 class="font-black text-xs text-white uppercase tracking-widest flex items-center gap-2 border-b border-white/5 pb-3">
                    <span class="material-icons text-primary text-sm">stars</span>
                    {{ __('card_details') }}
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('psa_cert_serial') }}</span>
                        <span class="font-mono font-bold text-indigo-400">{{ $order->product->cert ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('card_condition') }}</span>
                        <span class="font-bold text-neutral-200">{{ $order->product->condition ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('card_language') }}</span>
                        <span class="font-bold text-neutral-200">{{ $order->product->language ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('card_set_expansion') }}</span>
                        <span class="font-bold text-neutral-200">{{ $order->product->set ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('card_rarity') }}</span>
                        <span class="font-bold text-primary">{{ $order->product->rarity ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('filament.card_grading') }}</span>
                        <span class="font-bold text-neutral-200">{{ $order->product->grading ?? 'N/A' }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">{{ __('filament.card_grade') }}</span>
                        <span class="font-bold text-neutral-200">{{ $order->product->grade ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- PayPal Transaction Details -->
            <div class="bg-white/5 rounded-2xl border border-white/5 p-6 space-y-4">
                <h4 class="font-black text-xs text-white uppercase tracking-widest flex items-center gap-2 border-b border-white/5 pb-3">
                    <span class="material-icons text-primary text-sm">security</span>
                    PAYMENT TRANSACTION INFO
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">PAYMENT METHOD</span>
                        <span class="font-bold text-neutral-200 uppercase tracking-widest">PayPal Smart Payment</span>
                    </div>
                    <div>
                        <span class="text-neutral-500 block mb-0.5 uppercase tracking-widest text-[9px] font-black">PAYPAL TRANSACTION ID</span>
                        <span class="font-mono font-bold text-indigo-400 select-all">{{ $paypalTx ?? 'sb-paypal-tx-id' }}</span>
                    </div>
                </div>
            </div>

            <!-- Price Calculations -->
            <div class="space-y-3 pt-6 border-t border-white/5 text-sm">
                <div class="flex justify-between font-bold text-neutral-400">
                    <span class="uppercase tracking-widest text-xs">{{ __('subtotal') }}</span>
                    <span>{{ number_format($order->product_price) }}đ</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between font-bold text-emerald-400">
                    <span class="uppercase tracking-widest text-xs">{{ __('discount') }}</span>
                    <span>-{{ number_format($order->discount_amount) }}đ</span>
                </div>
                @endif
                <div class="flex justify-between items-center text-lg font-black text-white pt-4 border-t border-white/5">
                    <span class="uppercase tracking-wider text-xs">{{ __('total_payment') }}</span>
                    <div class="text-right">
                        <span class="text-primary text-2xl drop-shadow-[0_0_10px_rgba(74,222,128,0.4)] block">{{ number_format($order->final_amount) }}đ</span>
                        <span class="text-[10px] text-neutral-500 block font-normal tracking-wide mt-0.5">≈ ${{ round($order->final_amount / 25000, 2) }} USD</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('home') }}" class="px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all text-center active:scale-95">
                RETURN HOME
            </a>
            <a href="{{ route('user.profile') }}" class="px-8 py-4 btn-esport text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all text-center active:scale-95 shadow-xl shadow-primary/20 border-none">
                VIEW ORDER HISTORY
            </a>
        </div>
    </div>
</div>
@endsection
