{{-- Order Marquee Component --}}
<div class="border-y border-border py-1.5 md:py-3 overflow-hidden glass shadow-2xl">
    <div class="container mx-auto px-4 flex flex-col md:flex-row md:items-center gap-3 md:gap-4">
        <!-- Icon & Label -->
        <div class="flex items-center gap-2 shrink-0">
            <div class="bg-primary/10 border border-primary/20 rounded-lg p-2">
                <span class="material-icons text-primary text-xl">shopping_cart</span>
            </div>
            <span class="text-text-primary font-black text-[10px] md:text-sm uppercase tracking-[0.14em] md:tracking-[0.2em] drop-shadow-[0_0_8px_rgba(230,46,107,0.3)]">{{ __('list_category') }}:</span>
        </div>

        <!-- Static Category List -->
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap gap-2 md:gap-3">
                @forelse(($orderMarqueeCategories ?? collect()) as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="flex items-center gap-2 glass border border-white/10 rounded-lg px-3 md:px-4 py-2 shadow-lg hover:border-primary/50 transition-all">
                    <span class="material-icons text-primary text-sm">shopping_cart</span>
                    <span class="text-neutral-100 font-bold text-xs md:text-sm">
                        {{ $category->title }}
                    </span>
                </a>
                @empty
                <span class="text-neutral-500 text-xs font-bold uppercase tracking-widest">
                    {{ __('no_categories') }}
                </span>
                @endforelse
            </div>
        </div>
    </div>
</div>
