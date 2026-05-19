@extends('layouts.app')

@section('title', __('home_title'))
@section('description', __('home_desc'))

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <style>
        .fullscreen-banner-section {
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
        }
        
        .hero-swiper-fullscreen {
            height: 400px;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .hero-swiper-fullscreen {
                height: 550px;
            }
        }

        .categories-swiper {
            padding: 20px 0 !important;
        }

        .categories-swiper .swiper-button-next,
        .categories-swiper .swiper-button-prev {
            width: 40px !important;
            height: 40px !important;
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(8px) !important;
            border: 1px border border-white/10 !important;
            border-radius: 50% !important;
            color: #fff !important;
            transition: all 0.3s ease !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }

        .categories-swiper .swiper-button-next:after,
        .categories-swiper .swiper-button-prev:after {
            font-size: 18px !important;
            font-weight: 900 !important;
        }

        .categories-swiper .swiper-button-next:hover,
        .categories-swiper .swiper-button-prev:hover {
            background: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 20px rgba(230, 46, 107, 0.5) !important;
            color: #fff !important;
        }

        .categories-swiper .swiper-button-prev {
            left: -20px !important;
        }

        .categories-swiper .swiper-button-next {
            right: -20px !important;
        }

        /* TCG Graded Card Slab Styling */
        .slab-graded {
            position: relative;
            background: rgba(13, 17, 24, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 1.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .slab-graded::after {
            content: "";
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: linear-gradient(125deg, transparent 30%, rgba(255, 255, 255, 0.05) 40%, rgba(255, 255, 255, 0.12) 50%, rgba(255, 255, 255, 0.05) 60%, transparent 70%);
            background-size: 200% 200%;
            background-position: -100% 0;
            transition: background-position 0.6s ease;
            pointer-events: none;
        }

        .slab-graded:hover::after {
            background-position: 100% 0;
        }

        .slab-graded:hover {
            transform: translateY(-8px);
            border-color: rgba(230, 46, 107, 0.5);
            box-shadow: 0 15px 35px rgba(230, 46, 107, 0.25);
            background: rgba(20, 26, 38, 0.85);
        }

        /* Holographic Card Glow Overlay */
        .holo-card-glow {
            position: absolute;
            inset: 0;
            opacity: 0;
            mix-blend-mode: color-dodge;
            background: linear-gradient(105deg, 
                transparent 30%, 
                rgba(230, 46, 107, 0.25) 40%, 
                rgba(124, 58, 237, 0.25) 50%, 
                rgba(34, 197, 94, 0.25) 60%, 
                transparent 70%);
            background-size: 200% 200%;
            transition: all 0.5s ease;
            pointer-events: none;
            z-index: 5;
        }

        .slab-graded:hover .holo-card-glow {
            opacity: 1;
            background-position: 100% 100%;
        }

        .hero-swiper-fullscreen .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.4) !important;
            opacity: 1 !important;
        }

        .hero-swiper-fullscreen .swiper-pagination-bullet-active {
            background: var(--color-primary) !important;
            width: 24px !important;
            border-radius: 4px !important;
        }
    </style>
@endpush

@section('content')
    <!-- 1. Fullscreen Banner Section -->
    <section class="fullscreen-banner-section mb-12">
        <div class="swiper hero-swiper-fullscreen overflow-hidden shadow-2xl relative">
            <div class="swiper-wrapper">
                @forelse($banners as $banner)
                    <div class="swiper-slide relative overflow-hidden group bg-neutral-950">
                        <img src="{{ url('storage/' . $banner->image) }}" alt="{{ $banner->title ?? 'Promo' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#080A0F]/95 via-transparent to-transparent flex flex-col justify-end p-8 md:p-16">
                            @if($banner->title)
                                <h2 class="font-black text-3xl md:text-6xl text-white mb-2 uppercase tracking-tight leading-none drop-shadow-md">{{ $banner->title }}</h2>
                            @endif
                            @if($banner->description)
                                <p class="text-neutral-300 text-sm md:text-lg max-w-2xl mb-6 font-medium">{{ $banner->description }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Stunning Fallback Slideshow Banners -->
                    <div class="swiper-slide relative overflow-hidden group bg-gradient-to-br from-[#0B0E14] via-[#1E1B4B] to-[#581C87] flex flex-col justify-end p-8 md:p-16">
                        <div class="absolute top-10 right-10 md:top-16 md:right-16 shrink-0 animate-bounce">
                            <span class="text-6xl md:text-9xl drop-shadow-[0_0_20px_rgba(230,46,107,0.5)]">🃏</span>
                        </div>
                        <div class="relative z-10 space-y-4 max-w-2xl">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-primary/20 text-primary border border-primary/30 shadow-[0_0_15px_rgba(230,46,107,0.25)]">AUTHENTIC GRADED SLABS</span>
                            <h2 class="font-black text-3xl md:text-6xl text-white uppercase tracking-tighter leading-none">PREMIUM PSA TCG CARDS</h2>
                            <p class="text-neutral-300 text-sm md:text-base font-medium">Elevate your collection with verified high-grade cards. Authentic trading cards from Pokemon, One Piece & Magic: The Gathering.</p>
                            <div class="pt-2">
                                <a href="{{ route('products.index') }}" class="btn-esport text-xs px-6 py-2.5">EXPLORE SLABS</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide relative overflow-hidden group bg-gradient-to-br from-[#0B0E14] via-[#831843] to-[#4C0519] flex flex-col justify-end p-8 md:p-16">
                        <div class="absolute top-10 right-10 md:top-16 md:right-16 shrink-0 animate-pulse">
                            <span class="text-6xl md:text-9xl drop-shadow-[0_0_20px_rgba(124,58,237,0.5)]">📦</span>
                        </div>
                        <div class="relative z-10 space-y-4 max-w-2xl">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-secondary/20 text-secondary border border-secondary/30 shadow-[0_0_15px_rgba(124,58,237,0.25)]">GUARANTEE YOUR BOXES</span>
                            <h2 class="font-black text-3xl md:text-6xl text-white uppercase tracking-tighter leading-none">NEW RELEASE PRE-ORDERS</h2>
                            <p class="text-neutral-300 text-sm md:text-base font-medium">Secure your Booster boxes, Elite Trainer Boxes, and sealed collectible sets ahead of global releases.</p>
                            <div class="pt-2">
                                <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="btn-esport text-xs px-6 py-2.5">SECURE PRE-ORDER</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <div class="container mx-auto px-4 space-y-12 md:space-y-16">

        <!-- 2. List of Categories (Categories Swiper Carousel) -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl md:text-2xl font-black text-white uppercase flex items-center gap-3 tracking-wide">
                    <span class="w-2.5 h-6 bg-primary rounded-full"></span>
                    {{ __('featured_categories') }}
                </h2>
            </div>

            <!-- Desktop Swiper Carousel -->
            <div class="hidden lg:block relative">
                <div class="swiper categories-swiper overflow-hidden">
                    <div class="swiper-wrapper">
                        @forelse($categories as $category)
                            <div class="swiper-slide h-auto">
                                <a href="{{ route('categories.show', $category->slug) }}"
                                    class="group slab-graded p-4 flex flex-col items-center text-center transition-all h-full">
                                    <div class="holo-card-glow"></div>
                                    <div class="w-full h-40 mb-4 overflow-hidden rounded-xl relative border border-white/5 bg-neutral-950">
                                        <img alt="{{ $category->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                            src="{{ url('storage/' . $category->image) ?? 'https://via.placeholder.com/400x300' }}"
                                            loading="lazy" decoding="async">
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#080A0F]/80 to-transparent"></div>
                                    </div>
                                    <h3
                                        class="font-black text-sm mb-2 text-white group-hover:text-primary transition-colors uppercase tracking-widest flex items-center justify-center gap-2">
                                        {{ $category->title }}
                                    </h3>
                                    <p class="text-white/50 text-xs line-clamp-2 leading-relaxed">{!! strip_tags($category->description) !!}</p>
                                </a>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
                <!-- Navigation Buttons -->
                <div class="swiper-button-next categories-next"></div>
                <div class="swiper-button-prev categories-prev"></div>
            </div>

            <!-- Mobile Slider Grid View -->
            <div class="lg:hidden grid grid-cols-2 gap-4">
                @forelse($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}"
                        class="group slab-graded p-3 flex flex-col items-center text-center transition-all active:scale-[0.98]">
                        <div class="holo-card-glow"></div>
                        <div class="w-full h-28 mb-3 overflow-hidden rounded-lg relative border border-white/5 bg-neutral-950">
                            <img alt="{{ $category->title }}" class="w-full h-full object-cover"
                                src="{{ url('storage/' . $category->image) ?? 'https://via.placeholder.com/96' }}" loading="lazy"
                                decoding="async">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#080A0F]/80 to-transparent"></div>
                        </div>
                        <h3
                            class="font-black text-xs mb-1 text-white group-hover:text-primary transition-colors uppercase tracking-widest flex items-center justify-center gap-1.5 line-clamp-1 px-1">
                            {{ $category->title }}
                        </h3>
                        <p class="text-white/50 text-[10px] line-clamp-1">{!! strip_tags($category->description) !!}</p>
                    </a>
                @empty
                    <div class="col-span-2 text-center text-neutral-600 p-8 glass rounded-xl border border-white/5 text-sm">
                        {{ __('no_categories') }}</div>
                @endforelse
            </div>
        </section>

        <!-- 3. List Category Image Grid -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl md:text-2xl font-black text-white uppercase flex items-center gap-3 tracking-wide">
                    <span class="w-2.5 h-6 bg-primary rounded-full"></span>
                    {{ __('card_category') }}
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="group relative rounded-2xl overflow-hidden aspect-video border border-white/10 hover:border-primary/50 shadow-2xl transition-all duration-500">
                        <img src="{{ url('storage/' . $category->image) }}" alt="{{ $category->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#080A0F]/90 via-[#080A0F]/45 to-transparent flex flex-col justify-end p-6 space-y-2">
                            <h3 class="font-black text-lg md:text-xl text-white uppercase tracking-widest group-hover:text-primary transition-colors">{{ $category->title }}</h3>
                            <p class="text-neutral-300 text-xs line-clamp-2 leading-relaxed">{!! strip_tags($category->description) !!}</p>
                        </div>
                    </a>
                @empty
                @endforelse
            </div>
        </section>

        <!-- 4. List Product Pre-Order -->
        <section class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 relative">
                <h2 class="text-xl md:text-2xl font-black text-white uppercase flex items-center gap-3 tracking-wide">
                    <span class="w-2.5 h-6 bg-primary rounded-full"></span>
                    {{ __('pre_orders') }}
                </h2>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}"
                    class="text-neutral-400 hover:text-primary font-black uppercase tracking-widest text-[10px] md:text-xs flex items-center gap-1 transition-colors">
                    {{ __('view_all') }} <span class="material-icons text-sm">arrow_forward</span>
                </a>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 relative">
                @forelse($flashSaleProducts as $product)
                    <div class="slab-graded group transition-all relative">
                        <div class="holo-card-glow"></div>
                        <!-- Card Certification Slab Header -->
                        <div class="bg-neutral-900 border-b border-white/5 px-3 py-2 flex items-center justify-between text-[9px] font-black uppercase tracking-wider text-neutral-400">
                            <span>CERTIFIED TCG</span>
                            <span class="text-emerald-400">PRE-ORDER</span>
                        </div>

                        <!-- Card Media -->
                        <div class="relative overflow-hidden aspect-square border-b border-white/5 bg-neutral-950 flex items-center justify-center p-2">
                            <img alt="{{ $product->title }}"
                                class="w-full h-full object-contain group-hover:scale-105 transition duration-500 rounded"
                                src="{{ url('storage/' . $product->images[0]) ?? 'https://via.placeholder.com/400x225' }}"
                                loading="lazy" decoding="async">
                            @if($product->getDiscountPercent())
                                <div
                                    class="absolute top-2 right-2 bg-pink-500 text-white text-[9px] md:text-xs font-black px-2 py-0.5 rounded-full shadow-[0_0_10px_rgba(244,114,182,0.5)]">
                                    -{{ number_format($product->getDiscountPercent()) }}%
                                </div>
                            @endif
                            <div
                                class="absolute bottom-2 left-2 bg-bg-dark/90 backdrop-blur-sm px-2 py-0.5 rounded text-[8px] text-text-muted font-bold border border-white/5">
                                ID: {{ $product->id }}
                            </div>
                        </div>

                        <!-- Card Specifications & Pricing -->
                        <div class="p-4 space-y-3">
                            <div class="space-y-1">
                                <span class="text-[9px] text-neutral-500 font-bold uppercase tracking-widest block">{{ $product->category->title ?? 'TCG COLLECTIBLES' }}</span>
                                <h4 class="font-bold text-xs md:text-sm line-clamp-2 h-10 text-white group-hover:text-primary transition-colors tracking-tight leading-tight">
                                    {{ $product->title }}
                                </h4>
                            </div>

                            <div class="flex items-baseline gap-2 pt-1">
                                <span class="text-base md:text-lg font-black text-primary drop-shadow-[0_0_8px_rgba(230,46,107,0.4)]">
                                    {{ number_format($product->getFinalPrice()) }}đ
                                </span>
                                @if($product->sell_price)
                                    <span class="text-[10px] text-text-muted line-through font-bold">
                                        {{ number_format($product->sell_price) }}đ
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('products.show', $product->slug) }}"
                                class="block w-full btn-esport justify-center items-center py-2.5 rounded-xl text-center text-[10px] md:text-xs transition-all relative overflow-hidden">
                                <span class="material-icons text-sm">shopping_cart</span>
                                {{ __('buy_now') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 glass rounded-xl p-12 text-center border border-white/5">
                        <p class="text-neutral-500 text-xs uppercase font-black tracking-widest">{{ __('no_flash_sale') }}</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- 5. New Arrivals & Best Sellers! -->
        <section class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 relative">
                <h2 class="text-xl md:text-2xl font-black text-white uppercase flex items-center gap-3 tracking-wide">
                    <span class="w-2.5 h-6 bg-primary rounded-full"></span>
                    NEW ARRIVALS & BEST SELLERS!
                </h2>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}"
                    class="text-neutral-400 hover:text-primary font-black uppercase tracking-widest text-[10px] md:text-xs flex items-center gap-1 transition-colors">
                    {{ __('view_all') }} <span class="material-icons text-sm">arrow_forward</span>
                </a>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 relative">
                @forelse($flashSaleProducts->shuffle() as $product)
                    <div class="slab-graded group transition-all relative">
                        <div class="holo-card-glow"></div>
                        <!-- Card Certification Slab Header -->
                        <div class="bg-neutral-900 border-b border-white/5 px-3 py-2 flex items-center justify-between text-[9px] font-black uppercase tracking-wider text-neutral-400">
                            <span>BEST SELLER</span>
                            <span class="text-primary">PSA 10 GEM MT</span>
                        </div>

                        <!-- Card Media -->
                        <div class="relative overflow-hidden aspect-square border-b border-white/5 bg-neutral-950 flex items-center justify-center p-2">
                            <img alt="{{ $product->title }}"
                                class="w-full h-full object-contain group-hover:scale-105 transition duration-500 rounded"
                                src="{{ url('storage/' . $product->images[0]) ?? 'https://via.placeholder.com/400x225' }}"
                                loading="lazy" decoding="async">
                            @if($product->getDiscountPercent())
                                <div
                                    class="absolute top-2 right-2 bg-pink-500 text-white text-[9px] md:text-xs font-black px-2 py-0.5 rounded-full shadow-[0_0_10px_rgba(244,114,182,0.5)]">
                                    -{{ number_format($product->getDiscountPercent()) }}%
                                </div>
                            @endif
                            <div
                                class="absolute bottom-2 left-2 bg-bg-dark/90 backdrop-blur-sm px-2 py-0.5 rounded text-[8px] text-text-muted font-bold border border-white/5">
                                ID: {{ $product->id }}
                            </div>
                        </div>

                        <!-- Card Specifications & Pricing -->
                        <div class="p-4 space-y-3">
                            <div class="space-y-1">
                                <span class="text-[9px] text-neutral-500 font-bold uppercase tracking-widest block">{{ $product->category->title ?? 'TCG COLLECTIBLES' }}</span>
                                <h4 class="font-bold text-xs md:text-sm line-clamp-2 h-10 text-white group-hover:text-primary transition-colors tracking-tight leading-tight">
                                    {{ $product->title }}
                                </h4>
                            </div>

                            <div class="flex items-baseline gap-2 pt-1">
                                <span class="text-base md:text-lg font-black text-primary drop-shadow-[0_0_8px_rgba(230,46,107,0.4)]">
                                    {{ number_format($product->getFinalPrice()) }}đ
                                </span>
                                @if($product->sell_price)
                                    <span class="text-[10px] text-text-muted line-through font-bold">
                                        {{ number_format($product->sell_price) }}đ
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('products.show', $product->slug) }}"
                                class="block w-full btn-esport justify-center items-center py-2.5 rounded-xl text-center text-[10px] md:text-xs transition-all relative overflow-hidden">
                                <span class="material-icons text-sm">shopping_cart</span>
                                {{ __('buy_now') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 glass rounded-xl p-12 text-center border border-white/5">
                        <p class="text-neutral-500 text-xs uppercase font-black tracking-widest">{{ __('no_flash_sale') }}</p>
                    </div>
                @endforelse
            </div>
        </section>

    </div>  <!-- Đóng container mx-auto -->

    {{-- Schema JSON-LD --}}
    @push('schema')
        @php
            $websiteSchema = [
                "@@context" => "https://schema.org",
                "@@type" => "WebSite",
                "name" => "Rabby TCG - Graded Cards & Premium Collectibles",
                "url" => route('home'),
                "potentialAction" => [
                    "@@type" => "SearchAction",
                    "target" => route('home') . "?q={search_term_string}",
                    "query-input" => "required name=search_term_string"
                ]
            ];

            $orgSchema = [
                "@@context" => "https://schema.org",
                "@@type" => "Organization",
                "name" => "Rabby TCG",
                "url" => route('home'),
                "logo" => asset('images/logo.png'),
                "contactPoint" => [
                    "@@type" => "ContactPoint",
                    "telephone" => "0986526036",
                    "contactType" => "customer service"
                ],
                "sameAs" => [
                    "https://www.facebook.com/le.vietanh.939173"
                ]
            ];
        @endphp

        <script type="application/ld+json">
            @json($websiteSchema)
        </script>
        <script type="application/ld+json">
            @json($orgSchema)
        </script>
    @endpush

    {{-- Swiper JS --}}
    @push('scripts')
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Category swiper
                var categoriesSwiper = new Swiper('.categories-swiper', {
                    slidesPerView: 4,
                    spaceBetween: 16,
                    loop: true,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true
                    },
                    navigation: {
                        nextEl: '.categories-next',
                        prevEl: '.categories-prev',
                    },
                    breakpoints: {
                        768: { slidesPerView: 3, spaceBetween: 20 },
                        1024: { slidesPerView: 4, spaceBetween: 24 }
                    }
                });

                // Initialize Fullscreen Hero Banner swiper
                var heroSwiper = new Swiper('.hero-swiper-fullscreen', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    autoplay: {
                        delay: 4500,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    }
                });
            });
        </script>
    @endpush

@endsection