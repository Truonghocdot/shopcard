{{-- Order Marquee Component --}}
@php
$categories = \App\Models\Category::whereNull('parent_id')->get();
@endphp

<div class="border-y border-border py-1.5 md:py-3 overflow-hidden glass shadow-2xl">
    <div class="container mx-auto px-4 flex items-center gap-4">
        <!-- Icon & Label -->
        <div class="flex items-center gap-2 shrink-0">
            <div class="bg-primary/10 border border-primary/20 rounded-lg p-2">
                <span class="material-icons text-primary text-xl">shopping_cart</span>
            </div>
            <span class="text-text-primary font-black text-xs md:text-sm uppercase tracking-[0.2em] drop-shadow-[0_0_8px_rgba(230,46,107,0.3)]">{{ __('list_category') }}:</span>
        </div>

        <!-- Marquee Content -->
        <div class="flex-1 overflow-hidden min-w-0">
            <div class="marquee-content inline-flex flex-nowrap gap-6 items-center whitespace-nowrap">
                @if($categories->count() > 0)
                    @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="flex items-center gap-3 shrink-0 glass border border-white/10 rounded-lg px-4 py-2 shadow-xl group hover:border-primary/50 transition-all animate-pulse">
                        <span class="material-icons text-primary text-sm">shopping_cart</span>
                        <span class="text-neutral-100 font-bold text-sm">
                            {{ $category->title }}
                        </span>
                    </a>
                    @endforeach

                    <!-- Duplicate for seamless loop -->
                    @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="flex items-center gap-3 shrink-0 bg-bg-card border border-border rounded-lg px-4 py-2 hover:border-primary/50 transition-all shadow-lg">
                        <span class="material-icons text-primary/80 text-sm">shopping_cart</span>
                        <span class="text-text-primary font-bold text-sm">
                            {{ $category->title }}
                        </span>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes marquee {
        0% {
            transform: translate3d(50%, 0, 0);
        }

        100% {
            transform: translate3d(-50%, 0, 0);
        }
    }

    .marquee-content {
        width: max-content;
        animation: marquee 52s linear infinite;
        will-change: transform;
    }

    .marquee-content:hover {
        animation-play-state: paused;
    }
</style>
