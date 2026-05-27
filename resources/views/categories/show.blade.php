@extends('layouts.app')

@section('title', $category->meta_title ?? $category->title . ' - Rabby TCG')
@section('description', $category->meta_description ?? $category->description)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Category Header -->
    <div class="mb-12 text-center relative">
        <!-- Decorative background glow -->
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-indigo-500/10 blur-[100px] rounded-full pointer-events-none"></div>

        <h1 class="text-3xl font-black uppercase tracking-tight text-white mb-3 flex justify-center items-center gap-4 relative z-10">
            <span class="material-icons text-primary text-3xl animate-pulse">grid_view</span>
            {{ $category->title }}
        </h1>

        @if($category->description)
        <p class="text-neutral-400 max-w-2xl mx-auto font-bold">{{ $category->description }}</p>
        @endif
        <div class="h-1 w-32 bg-linear-to-r from-transparent via-primary to-transparent mx-auto rounded-full mt-6"></div>
    </div>

    @if($products->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        @foreach($products as $product)
        <div class="card-esport group transition-all relative">
            <div class="relative overflow-hidden aspect-video">
                <img alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" src="{{ url('storage/'.$product->images[0]) ?? 'https://via.placeholder.com/400x225' }}" loading="lazy" decoding="async">
                @if($product->quantity <= 0)
                <div class="absolute top-2 left-2 z-10">
                    <img src="{{ asset('images/soldout-stamp.png') }}" alt="{{ __('sold_out') }}" class="w-16 md:w-20 h-auto drop-shadow-[0_0_10px_rgba(244,114,182,0.35)]" loading="lazy" decoding="async">
                </div>
                @endif
                @if($product->getDiscountPercent())
                <div class="absolute top-2 right-2 bg-pink-500 text-white text-xs md:text-sm font-black px-2 py-1 rounded-full shadow-[0_0_10px_rgba(244,114,182,0.5)]">
                    -{{ number_format($product->getDiscountPercent()) }}%
                </div>
                @endif
                <div class="absolute bottom-2 left-2 bg-bg-dark/80 backdrop-blur-sm px-2 py-0.5 rounded text-[10px] text-text-muted font-bold border border-border">
                    ID: {{ $product->id }}
                </div>
            </div>
            <div class="p-4">
                <h4 class="font-bold text-sm mb-3 line-clamp-2 h-10 text-neutral-100 group-hover:text-primary transition-colors tracking-tight">{{ $product->title }}</h4>
                <div class="flex flex-col mb-4">
                    @if($product->sell_price)
                    <span class="text-xs text-text-muted line-through">{{ number_format($product->sell_price) }} đ</span>
                    @endif
                    <span class="text-xl font-black text-primary drop-shadow-[0_0_8px_rgba(230,46,107,0.4)]">{{ number_format($product->getFinalPrice()) }} <span class="text-sm">đ</span></span>
                </div>
                <a href="{{ route('products.show', $product->slug) }}" class="block w-full {{ $product->quantity <= 0 ? 'bg-white/5 border border-white/10 text-neutral-400 cursor-not-allowed' : 'btn-esport' }} justify-center items-center py-2.5 rounded-lg text-center text-[10px] md:text-sm transition-all group-hover:gap-3 relative overflow-hidden">
                    <span class="material-icons text-sm mr-1">shopping_cart</span>
                    {{ $product->quantity <= 0 ? __('sold_out') : __('buy_now') }}
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->links('vendor.pagination.tailwind') }}
    </div>
    @else
    <div class="glass rounded-2xl border border-white/10 p-12 text-center">
        <span class="material-icons text-6xl text-neutral-800 mb-6 drop-shadow-[0_0_15px_rgba(255,255,255,0.05)]">inventory_2</span>
        <p class="text-2xl font-black mb-3 text-white uppercase tracking-tighter">{{ __('no_products_found') }}</p>
        <p class="text-neutral-600 font-bold">{{ __('no_products_in_category') }}</p>
    </div>
    @endif
</div>

@endsection
