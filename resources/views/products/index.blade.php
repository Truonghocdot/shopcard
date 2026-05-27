@extends('layouts.app')

@section('title', __('products_meta_title'))
@section('description', __('products_meta_desc'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['name' => __('home'), 'url' => route('home')],
        ['name' => __('products'), 'url' => route('products.index')]
    ]" />

    <div class="mb-12 text-center relative">
        <!-- Decorative background glow -->
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-secondary/10 blur-[100px] rounded-full pointer-events-none"></div>

        <h1 class="text-3xl md:text-5xl font-black uppercase tracking-tight text-text-primary mb-3 flex justify-center items-center gap-4 relative z-10">
            <span class="material-icons text-primary text-3xl md:text-5xl animate-pulse">shopping_bag</span>
            {{ __('products_title') }}
        </h1>
        <p class="text-text-muted font-black uppercase tracking-[0.3em] text-[10px] md:text-xs">{{ __('products_subtitle') }}</p>
        <div class="h-1 w-32 bg-linear-to-r from-transparent via-primary to-transparent mx-auto rounded-full mt-8"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filter Sidebar -->
        <aside class="lg:col-span-1">
            <div class="bg-bg-card rounded-2xl border border-border shadow-3xl p-6 sticky top-24">
                <h2 class="text-xl font-black mb-8 flex items-center gap-3 text-text-primary uppercase tracking-tighter">
                    <span class="material-icons text-primary">filter_list</span>
                    {{ __('filter') }}
                </h2>

                <form method="GET" action="{{ route('products.index') }}">
                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-[10px] font-black mb-3 text-text-muted uppercase tracking-[0.2em]">{{ __('search') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('search') }}..."
                            class="w-full bg-bg-dark/50 border border-border focus:border-primary rounded-xl px-4 py-3 text-text-secondary placeholder-text-muted outline-hidden transition-all">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <label class="block text-[10px] font-black mb-3 text-text-muted uppercase tracking-[0.2em]">{{ __('category') }}</label>
                        <select name="category" class="w-full bg-bg-dark/50 border border-border focus:border-primary focus:ring-primary/20 rounded-xl px-4 py-3 text-text-secondary outline-hidden transition-all">
                            <option value="" class="bg-neutral-950">{{ __('all_categories') }}</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }} class="bg-neutral-950">{{ $cat->title }}</option>
                            @if($cat->children->count() > 0)
                            @foreach($cat->children as $child)
                            <option value="{{ $child->id }}" {{ request('category') == $child->id ? 'selected' : '' }} class="bg-neutral-950">&nbsp;&nbsp;↳ {{ $child->title }}</option>
                            @endforeach
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Card Type -->
                    <div class="mb-6">
                        <label class="block text-[10px] font-black mb-3 text-text-muted uppercase tracking-[0.2em]">{{ __('filament.card_type') }}</label>
                        <select name="card_type" class="w-full bg-bg-dark/50 border border-border focus:border-primary rounded-xl px-4 py-3 text-text-secondary outline-hidden transition-all">
                            <option value="" class="bg-neutral-950">{{ __('all_categories') }}</option>
                            @foreach($cardTypeOptions as $val => $label)
                            <option value="{{ $val }}" {{ request('card_type') == $val ? 'selected' : '' }} class="bg-neutral-950">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Condition -->
                    <div class="mb-6">
                        <label class="block text-[10px] font-black mb-3 text-text-muted uppercase tracking-[0.2em]">{{ __('card_condition') }}</label>
                        <select name="condition" class="w-full bg-bg-dark/50 border border-border focus:border-primary rounded-xl px-4 py-3 text-text-secondary outline-hidden transition-all">
                            <option value="" class="bg-neutral-950">— Any —</option>
                            @foreach($conditionOptions as $val => $label)
                            <option value="{{ $val }}" {{ request('condition') == $val ? 'selected' : '' }} class="bg-neutral-950">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Language -->
                    <div class="mb-6">
                        <label class="block text-[10px] font-black mb-3 text-text-muted uppercase tracking-[0.2em]">{{ __('card_language') }}</label>
                        <select name="language" class="w-full bg-bg-dark/50 border border-border focus:border-primary rounded-xl px-4 py-3 text-text-secondary outline-hidden transition-all">
                            <option value="" class="bg-neutral-950">— Any —</option>
                            @foreach($languageOptions as $val => $label)
                            <option value="{{ $val }}" {{ request('language') == $val ? 'selected' : '' }} class="bg-neutral-950">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Grading -->
                    <div class="mb-6">
                        <label class="block text-[10px] font-black mb-3 text-text-muted uppercase tracking-[0.2em]">{{ __('filament.card_grading') }}</label>
                        <select name="grading" class="w-full bg-bg-dark/50 border border-border focus:border-primary rounded-xl px-4 py-3 text-text-secondary outline-hidden transition-all">
                            <option value="" class="bg-neutral-950">— Any —</option>
                            @foreach($gradingOptions as $val => $label)
                            <option value="{{ $val }}" {{ request('grading') == $val ? 'selected' : '' }} class="bg-neutral-950">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Set / Expansion -->
                    <div class="mb-6">
                        <label class="block text-[10px] font-black mb-3 text-text-muted uppercase tracking-[0.2em]">{{ __('card_set_expansion') }}</label>
                        <input type="text" name="set" value="{{ request('set') }}"
                            placeholder="e.g. Base Set, Scarlet & Violet..."
                            class="w-full bg-bg-dark/50 border border-border focus:border-primary rounded-xl px-4 py-3 text-text-secondary placeholder-text-muted outline-hidden transition-all">
                    </div>

                    <!-- Rarity -->
                    <div class="mb-6">
                        <label class="block text-[10px] font-black mb-3 text-text-muted uppercase tracking-[0.2em]">{{ __('card_rarity') }}</label>
                        <input type="text" name="rarity" value="{{ request('rarity') }}"
                            placeholder="e.g. Ultra Rare, Secret Rare..."
                            class="w-full bg-bg-dark/50 border border-border focus:border-primary rounded-xl px-4 py-3 text-text-secondary placeholder-text-muted outline-hidden transition-all">
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-[10px] font-black mb-3 text-text-muted uppercase tracking-[0.2em]">{{ __('price_range') }}</label>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" placeholder="{{ __('price_from') }}" value="{{ request('min_price') }}" class="w-1/2 bg-bg-dark/50 border border-border focus:border-primary rounded-xl px-3 py-3 text-text-secondary placeholder-text-muted outline-hidden transition-all">
                            <input type="number" name="max_price" placeholder="{{ __('price_to') }}" value="{{ request('max_price') }}" class="w-1/2 bg-bg-dark/50 border border-border focus:border-primary rounded-xl px-3 py-3 text-text-secondary placeholder-text-muted outline-hidden transition-all">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="mb-8">
                        <label class="block text-[10px] font-black mb-3 text-text-muted uppercase tracking-[0.2em]">{{ __('sort_by') }}</label>
                        <select name="sort" class="w-full bg-bg-dark/50 border border-border focus:border-primary rounded-xl px-4 py-3 text-text-secondary outline-hidden transition-all">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }} class="bg-bg-dark">{{ __('newest') }}</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }} class="bg-bg-dark">{{ __('price_low_to_high') }}</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }} class="bg-bg-dark">{{ __('price_high_to_low') }}</option>
                            <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }} class="bg-bg-dark">{{ __('highest_discount') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full btn-esport py-4 rounded-xl uppercase tracking-widest text-center text-xs font-black shadow-lg shadow-primary/20 border-none">
                        {{ __('apply') }}
                    </button>
                    <a href="{{ route('products.index') }}" class="block w-full text-center bg-bg-dark/80 hover:bg-white/5 border border-border text-text-muted hover:text-text-primary px-4 py-4 rounded-xl mt-3 transition-all uppercase tracking-widest text-xs font-black">
                        {{ __('clear_filter') }}
                    </a>
                </form>
            </div>
        </aside>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
                @foreach($products as $product)
                <div class="card-esport group transition-all hover:scale-[1.02] relative">
                    <div class="relative overflow-hidden aspect-video">
                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
                            <img alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" src="{{ url('storage/'.$product->images[0]) ?? 'https://via.placeholder.com/400x225' }}" loading="lazy">
                        </a>
                        @if($product->quantity <= 0)
                        <div class="absolute top-2 left-2 z-10 pointer-events-none">
                            <img src="{{ asset('images/soldout-stamp.png') }}" alt="{{ __('sold_out') }}" class="w-16 md:w-20 h-auto drop-shadow-[0_0_10px_rgba(244,114,182,0.35)]" loading="lazy" decoding="async">
                        </div>
                        @endif
                        @if($product->getDiscountPercent())
                        <div class="absolute top-2 right-2 bg-pink-500 text-white text-xs md:text-sm font-black px-2 py-1 rounded-full shadow-[0_0_10px_rgba(244,114,182,0.5)]">
                             -{{ number_format($product->getDiscountPercent()) }}%
                        </div>
                        @endif
                        <div class="absolute bottom-2 left-2 bg-neutral-950/80 backdrop-blur-sm px-2 py-0.5 rounded text-[10px] text-neutral-300 font-bold border border-white/10">
                            ID: {{ $product->id }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-sm mb-3 line-clamp-2 h-10 text-text-primary group-hover:text-primary transition-colors tracking-tight">{{ $product->title }}</h4>
                        <div class="flex flex-col mb-4">
                            @if($product->sell_price)
                            <span class="text-xs text-text-muted line-through">{{ number_format($product->sell_price) }} đ</span>
                            @endif
                            <span class="text-xl font-black text-primary drop-shadow-[0_0_8px_rgba(230,46,107,0.4)]">{{ number_format($product->getFinalPrice()) }} <span class="text-sm">đ</span></span>
                        </div>
                        @if($product->quantity <= 0)
                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full py-2 rounded-xl text-center transition-all">
                            <img src="{{ asset('images/soldout-stamp.png') }}" alt="{{ __('sold_out') }}" class="w-24 h-auto mx-auto drop-shadow-[0_0_10px_rgba(244,114,182,0.35)]" loading="lazy" decoding="async">
                        </a>
                        @else
                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full btn-esport justify-center items-center py-2.5 rounded-xl text-center text-xs md:text-sm font-black uppercase tracking-widest transition-all relative overflow-hidden group-hover:gap-3">
                            <span class="material-icons text-sm mr-1">shopping_cart</span>
                            {{ __('read_more') }}
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @else
            <div class="glass rounded-2xl border border-white/10 p-12 text-center">
                <span class="material-icons text-6xl text-neutral-800 mb-6 drop-shadow-[0_0_15px_rgba(255,255,255,0.05)]">search_off</span>
                <p class="text-2xl font-black mb-3 text-white uppercase tracking-tighter">{{ __('no_products_found') }}</p>
                <p class="text-neutral-600 font-bold">{{ __('try_another_filter') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
