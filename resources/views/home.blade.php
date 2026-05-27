@extends('layouts.app')

@section('title', __('home_title'))
@section('description', __('home_desc'))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<style>
.fullscreen-banner-section {
    width: 100vw; position: relative;
    left: 50%; right: 50%;
    margin-left: -50vw; margin-right: -50vw;
}
/* Mobile: 1:1 → Tablet: 2:1 → Desktop: 5:1 */
.hero-swiper-fullscreen { width: 100%; aspect-ratio: 1/1; }
@media (min-width: 640px)  { .hero-swiper-fullscreen { aspect-ratio: 2/1; } }
@media (min-width: 1024px) { .hero-swiper-fullscreen { aspect-ratio: 5/1; } }
.hero-swiper-fullscreen .swiper-slide { height: 100%; }
.hero-swiper-fullscreen .swiper-pagination-bullet { background: rgba(255,255,255,0.4) !important; opacity: 1 !important; }
.hero-swiper-fullscreen .swiper-pagination-bullet-active { background: var(--color-primary) !important; width: 24px !important; border-radius: 4px !important; }
.hero-swiper-fullscreen .swiper-pagination {
    bottom: 18px !important;
}
.hero-banner-image {
    filter: brightness(1.08) saturate(1.04);
}
.slab-graded {
    position: relative; background: rgba(13,17,24,0.75); backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.06); border-radius: 1.5rem;
    transition: all 0.4s cubic-bezier(0.175,0.885,0.32,1.275);
    overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}
.slab-graded::after {
    content: ""; position: absolute; top: 0; right: 0; bottom: 0; left: 0;
    background: linear-gradient(125deg, transparent 30%, rgba(255,255,255,0.05) 40%, rgba(255,255,255,0.12) 50%, rgba(255,255,255,0.05) 60%, transparent 70%);
    background-size: 200% 200%; background-position: -100% 0;
    transition: background-position 0.6s ease; pointer-events: none;
}
.slab-graded:hover::after { background-position: 100% 0; }
.slab-graded:hover { transform: translateY(-8px); border-color: rgba(230,46,107,0.5); box-shadow: 0 15px 35px rgba(230,46,107,0.25); }
.holo-card-glow {
    position: absolute; inset: 0; opacity: 0; mix-blend-mode: color-dodge;
    background: linear-gradient(105deg, transparent 30%, rgba(230,46,107,0.25) 40%, rgba(124,58,237,0.25) 50%, rgba(34,197,94,0.25) 60%, transparent 70%);
    background-size: 200% 200%; transition: all 0.5s ease; pointer-events: none; z-index: 5;
}
.slab-graded:hover .holo-card-glow { opacity: 1; background-position: 100% 100%; }
</style>
@endpush

@section('content')

{{-- ① TRUST BAR --}}
<div class="w-full bg-neutral-950/80 border-b border-white/5 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-center overflow-x-auto no-scrollbar">
            <div class="flex items-center gap-2 px-5 py-3 shrink-0 border-r border-white/5">
                <span class="material-icons text-primary text-base">verified</span>
                <span class="text-[10px] font-black uppercase tracking-widest text-neutral-300 whitespace-nowrap">{{ __('trust_authentic') }}</span>
            </div>
            <div class="flex items-center gap-2 px-5 py-3 shrink-0">
                <span class="material-icons text-primary text-base">support_agent</span>
                <span class="text-[10px] font-black uppercase tracking-widest text-neutral-300 whitespace-nowrap">{{ __('trust_support_247') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ② HERO BANNER --}}
<section class="fullscreen-banner-section">
    <div class="swiper hero-swiper-fullscreen overflow-hidden relative">
        <div class="swiper-wrapper">
            @forelse($banners as $banner)
                <div class="swiper-slide relative overflow-hidden bg-neutral-950">
                    <picture class="w-full h-full">
                        {{-- Ảnh mobile: dùng mobile_image nếu có, fallback về image --}}
                        @if($banner->mobile_image)
                            <source media="(max-width: 639px)" srcset="{{ url('storage/'.$banner->mobile_image) }}">
                        @endif
                        <img
                            src="{{ url('storage/'.$banner->image) }}"
                            alt="Banner"
                            class="hero-banner-image w-full h-full object-cover"
                        >
                    </picture>
                </div>
            @empty
                <div class="swiper-slide bg-neutral-950 flex items-center justify-center">
                    <p class="text-neutral-700 font-black uppercase tracking-widest text-sm">{{ __('premium_tcg_shop') }}</p>
                </div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>

{{-- ③–⑦ MAIN CONTENT --}}
<div class="max-w-7xl mx-auto px-4 py-10 space-y-16">

    {{-- ③ TCG COLLECTIONS --}}
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg md:text-2xl font-black text-white uppercase flex items-center gap-3 tracking-wider">
                <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                {{ __('featured_categories') }}
            </h2>
            <a href="{{ route('products.index') }}" class="text-neutral-400 hover:text-primary font-black uppercase tracking-widest text-[10px] flex items-center gap-1 transition-colors">
                {{ __('view_all') }} <span class="material-icons text-sm">arrow_forward</span>
            </a>
        </div>

        {{-- Desktop: uniform grid --}}
        <div class="hidden md:grid grid-cols-4 auto-rows-[180px] gap-4">
            @foreach($categories->take(8) as $category)
                <a href="{{ route('categories.show', $category->slug) }}"
                    class="group relative rounded-2xl overflow-hidden border border-white/8 hover:border-primary/50 shadow-xl transition-all duration-500">
                    <img src="{{ url('storage/'.$category->image) }}" alt="{{ $category->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#080A0F]/90 via-[#080A0F]/20 to-transparent flex flex-col justify-end p-4">
                        <h3 class="font-black text-sm text-white uppercase tracking-widest group-hover:text-primary transition-colors leading-tight line-clamp-2">
                            {{ $category->title }}
                        </h3>
                        @if($category->description)
                            <p class="text-neutral-400 text-[10px] mt-1 line-clamp-2">{!! strip_tags($category->description) !!}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Mobile: 2-col grid --}}
        <div class="md:hidden grid grid-cols-2 gap-3">
            @foreach($categories->take(8) as $category)
                <a href="{{ route('categories.show', $category->slug) }}"
                    class="group relative rounded-xl overflow-hidden border border-white/8 aspect-video">
                    <img src="{{ url('storage/'.$category->image) }}" alt="{{ $category->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#080A0F]/90 to-transparent flex items-end p-3">
                        <h3 class="font-black text-[10px] text-white uppercase tracking-widest line-clamp-1">{{ $category->title }}</h3>
                    </div>
                </a>
            @endforeach
        </div>

        @if($categories->count() > 8)
            <div class="text-center">
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-primary/30 text-neutral-300 hover:text-white font-black uppercase tracking-widest text-xs transition-all">
                    <span class="material-icons text-sm">expand_more</span>
                    {{ __('home_show_more_categories') }}
                </a>
            </div>
        @endif
    </section>

    {{-- ④ FLASH SALE / PRE-ORDERS --}}
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg md:text-2xl font-black text-white uppercase flex items-center gap-3 tracking-wider">
                <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                {{ __('flash_sale') }}
            </h2>
            <a href="{{ route('products.index', ['sort' => 'discount']) }}" class="text-neutral-400 hover:text-primary font-black uppercase tracking-widest text-[10px] flex items-center gap-1 transition-colors">
                {{ __('view_all') }} <span class="material-icons text-sm">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
            @forelse($flashSaleProducts->take(8) as $product)
                <div class="slab-graded group flex flex-col">
                    <div class="holo-card-glow"></div>
                    <div class="bg-neutral-900/80 border-b border-white/5 px-3 py-2 flex items-center justify-between text-[9px] font-black uppercase tracking-wider">
                        <span class="text-neutral-500">{{ $product->category->title ?? 'TCG' }}</span>
                        @if($product->getDiscountPercent())
                            <span class="text-pink-400">-{{ number_format($product->getDiscountPercent()) }}% OFF</span>
                        @else
                            <span class="text-indigo-400 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-pulse"></span>HOT</span>
                        @endif
                    </div>
                    <div class="relative overflow-hidden aspect-square border-b border-white/5 bg-neutral-950 flex items-center justify-center p-2">
                        <img alt="{{ $product->title }}"
                            class="w-full h-full object-contain group-hover:scale-105 transition duration-500 rounded"
                            src="{{ isset($product->images[0]) ? url('storage/'.$product->images[0]) : 'https://via.placeholder.com/400' }}"
                            loading="lazy" decoding="async">
                    </div>
                    <div class="p-4 space-y-3 flex-1 flex flex-col">
                        <h4 class="font-bold text-xs md:text-sm line-clamp-2 text-white group-hover:text-primary transition-colors tracking-tight leading-tight flex-1">
                            {{ $product->title }}
                        </h4>
                        <div class="flex items-baseline gap-2">
                            <span class="text-base font-black text-primary drop-shadow-[0_0_8px_rgba(230,46,107,0.4)]">{{ number_format($product->getFinalPrice()) }}đ</span>
                            @if($product->sell_price && $product->sell_price > $product->getFinalPrice())
                                <span class="text-[10px] text-neutral-600 line-through font-bold">{{ number_format($product->sell_price) }}đ</span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}"
                            class="flex items-center justify-center gap-2 w-full btn-esport py-2.5 rounded-xl text-[10px] md:text-xs transition-all">
                            <span class="material-icons text-sm">visibility</span>{{ __('view_details') }}
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

    {{-- ⑤ NEW ARRIVALS --}}
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg md:text-2xl font-black text-white uppercase flex items-center gap-3 tracking-wider">
                <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                {{ __('new_releases') }}
            </h2>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-neutral-400 hover:text-primary font-black uppercase tracking-widest text-[10px] flex items-center gap-1 transition-colors">
                {{ __('view_all') }} <span class="material-icons text-sm">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
            @forelse($flashSaleProducts->shuffle()->take(8) as $product)
                <div class="slab-graded group flex flex-col">
                    <div class="holo-card-glow"></div>
                    <div class="bg-neutral-900/80 border-b border-white/5 px-3 py-2 flex items-center justify-between text-[9px] font-black uppercase tracking-wider">
                        <span class="text-neutral-500">{{ $product->category->title ?? 'TCG' }}</span>
                        <span class="{{ $product->quantity > 0 ? 'text-emerald-400' : 'text-pink-400' }}">{{ $product->quantity > 0 ? __('in_stock') : __('sold_out') }}</span>
                    </div>
                    <div class="relative overflow-hidden aspect-square border-b border-white/5 bg-neutral-950 flex items-center justify-center p-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
                            <img alt="{{ $product->title }}"
                                class="w-full h-full object-contain group-hover:scale-105 transition duration-500 rounded"
                                src="{{ isset($product->images[0]) ? url('storage/'.$product->images[0]) : 'https://via.placeholder.com/400' }}"
                                loading="lazy" decoding="async">
                        </a>
                        @if($product->quantity <= 0)
                            <div class="absolute top-2 left-2 z-10 pointer-events-none">
                                <img src="{{ asset('images/soldout-stamp.png') }}" alt="{{ __('sold_out') }}" class="w-14 md:w-16 h-auto drop-shadow-[0_0_10px_rgba(244,114,182,0.35)]" loading="lazy" decoding="async">
                            </div>
                        @endif
                        @if($product->getDiscountPercent())
                            <div class="absolute top-2 right-2 bg-pink-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">
                                -{{ number_format($product->getDiscountPercent()) }}%
                            </div>
                        @endif
                        <div class="absolute bottom-2 left-2 bg-[#080A0F]/90 px-2 py-0.5 rounded text-[8px] text-neutral-500 font-bold border border-white/5">
                            #{{ $product->id }}
                        </div>
                    </div>
                    <div class="p-4 space-y-3 flex-1 flex flex-col">
                        <h4 class="font-bold text-xs md:text-sm line-clamp-2 text-white group-hover:text-primary transition-colors tracking-tight leading-tight flex-1">
                            {{ $product->title }}
                        </h4>
                        <div class="flex items-baseline gap-2">
                            <span class="text-base font-black text-primary drop-shadow-[0_0_8px_rgba(230,46,107,0.4)]">{{ number_format($product->getFinalPrice()) }}đ</span>
                            @if($product->getDiscountPercent() && $product->sell_price && $product->sell_price > $product->getFinalPrice())
                                <span class="text-[10px] text-neutral-600 line-through font-bold">{{ number_format($product->sell_price) }}đ</span>
                            @endif
                        </div>
                        @if($product->quantity <= 0)
                        <a href="{{ route('products.show', $product->slug) }}" class="flex items-center justify-center w-full py-2 rounded-xl transition-all">
                            <img src="{{ asset('images/soldout-stamp.png') }}" alt="{{ __('sold_out') }}" class="w-20 md:w-24 h-auto drop-shadow-[0_0_10px_rgba(244,114,182,0.35)]" loading="lazy" decoding="async">
                        </a>
                        @else
                        <a href="{{ route('products.show', $product->slug) }}"
                            class="flex items-center justify-center gap-2 w-full btn-esport py-2.5 rounded-xl text-[10px] md:text-xs transition-all">
                            <span class="material-icons text-sm">shopping_cart</span>{{ __('add_to_cart') }}
                        </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-4 glass rounded-xl p-12 text-center border border-white/5">
                    <p class="text-neutral-500 text-xs uppercase font-black tracking-widest">{{ __('no_flash_sale') }}</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ⑥ LATEST NEWS --}}
    @if($latestNews->count())
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg md:text-2xl font-black text-white uppercase flex items-center gap-3 tracking-wider">
                <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                {{ __('latest_news') }}
            </h2>
            <a href="{{ route('news.index') }}" class="text-neutral-400 hover:text-primary font-black uppercase tracking-widest text-[10px] flex items-center gap-1 transition-colors">
                {{ __('view_all') }} <span class="material-icons text-sm">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($latestNews->take(4) as $news)
                <a href="{{ route('news.show', $news->slug) }}" class="group glass rounded-2xl border border-white/8 hover:border-primary/40 overflow-hidden transition-all duration-300 flex flex-col">
                    @if($news->thumbnail)
                        <div class="aspect-video overflow-hidden bg-neutral-950">
                            <img src="{{ url('storage/'.$news->thumbnail) }}" alt="{{ $news->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    @endif
                    <div class="p-4 flex flex-col flex-1 space-y-2">
                        <p class="text-[9px] text-neutral-500 font-black uppercase tracking-widest">{{ $news->created_at->format('M d, Y') }}</p>
                        <h3 class="font-black text-sm text-white group-hover:text-primary transition-colors line-clamp-2 leading-tight flex-1">
                            {{ $news->title }}
                        </h3>
                        <span class="text-primary text-[10px] font-black uppercase tracking-widest flex items-center gap-1">
                            {{ __('read_more') }} <span class="material-icons text-xs">arrow_forward</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ⑦ TRUST FOOTER BAR --}}
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['icon' => 'local_shipping',  'title' => __('fast_shipping_title'),         'desc' => __('fast_shipping_desc')],
            ['icon' => 'verified_user',   'title' => __('pre_order_guarantee_title'),   'desc' => __('pre_order_guarantee_desc')],
            ['icon' => 'lock',            'title' => __('secure_payments_title'),       'desc' => __('secure_payments_desc')],
            ['icon' => 'price_check',     'title' => __('price_match_title'),           'desc' => __('price_match_desc')],
        ] as $item)
        <div class="glass rounded-2xl border border-white/8 p-5 flex flex-col items-center text-center gap-3">
            <span class="material-icons text-primary text-3xl">{{ $item['icon'] }}</span>
            <h4 class="font-black text-xs text-white uppercase tracking-widest">{{ $item['title'] }}</h4>
            <p class="text-neutral-500 text-[10px] leading-relaxed">{{ $item['desc'] }}</p>
        </div>
        @endforeach
    </section>

</div>{{-- end max-w-7xl --}}

@push('scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.hero-swiper-fullscreen', {
        slidesPerView: 1, loop: true,
        effect: 'fade', fadeEffect: { crossFade: true },
        autoplay: { delay: 4500, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true }
    });
});
</script>
@endpush

@endsection
