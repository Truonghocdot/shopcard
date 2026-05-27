<div>
    @if($isSoldOut)
        <div class="w-full sm:w-auto py-2 flex items-center justify-center">
            <img src="{{ asset('images/soldout-stamp.png') }}" alt="{{ __('sold_out') }}" class="w-28 md:w-32 h-auto drop-shadow-[0_0_10px_rgba(244,114,182,0.35)]" loading="lazy" decoding="async">
        </div>
    @elseif($inCart)
        <button
            wire:click="goToCart"
            class="w-full sm:w-auto btn-esport py-5 px-12 rounded-2xl flex items-center justify-center gap-3 uppercase tracking-widest text-base font-black border-none shadow-2xl shadow-primary/30 active:scale-95 transition-all">
            <span class="material-icons text-xl">shopping_cart</span>
            {{ __('cart.view_cart') }}
        </button>
    @else
        <button
            wire:click="add"
            wire:loading.attr="disabled"
            wire:target="add"
            class="w-full sm:w-auto btn-esport py-5 px-12 rounded-2xl flex items-center justify-center gap-3 uppercase tracking-widest text-base font-black border-none shadow-2xl shadow-primary/30 active:scale-95 transition-all disabled:opacity-60">
            <span wire:loading.remove wire:target="add" class="material-icons text-xl">add_shopping_cart</span>
            <span wire:loading wire:target="add" class="material-icons animate-spin text-xl">refresh</span>
            <span wire:loading.remove wire:target="add">{{ __('add_to_cart') }}</span>
            <span wire:loading wire:target="add">{{ __('cart.adding') }}</span>
        </button>
    @endif

    @if(session('cart_error'))
    <p class="text-pink-500 text-[10px] font-black uppercase tracking-widest mt-2">{{ session('cart_error') }}</p>
    @endif
</div>
