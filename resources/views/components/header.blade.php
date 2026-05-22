<!-- Top Info Bar -->
@php
    $taglineSource = $siteSettings['site_tagline'] ?? __('premium_tcg_shop');
    $taglineItems = collect(preg_split('/\s*[|•]\s*/u', $taglineSource))
        ->map(fn ($item) => trim((string) $item))
        ->filter()
        ->values();

    if ($taglineItems->isEmpty()) {
        $taglineItems = collect([__('premium_tcg_shop')]);
    }
@endphp

<div class="bg-black py-1 overflow-hidden whitespace-nowrap border-b border-white/5 relative z-60">
    <div class="container mx-auto px-2 md:px-4">
        <div class="header-tagline-stage">
            @foreach($taglineItems as $index => $taglineItem)
                <span
                    class="header-tagline-item text-primary font-black text-[10px] uppercase tracking-[0.2em] inline-flex items-center justify-center gap-3"
                    data-tagline-item
                >
                    <span class="text-white/70">•</span>
                    <span>{{ $taglineItem }}</span>
                </span>
            @endforeach
        </div>
    </div>
</div>

<header class="sticky top-0 z-50 glass border-b border-border shadow-2xl overflow-visible h-[70px] flex items-center isolate">
    <!-- Removed Tet decorative branches and firecrackers -->

    <div class="container mx-auto px-2 md:px-4 py-2 md:py-3 flex items-center justify-between relative z-40 gap-2 md:gap-4">
        <div class="flex items-center gap-3 md:gap-6 shrink-0 min-w-0">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group relative">
                <img src="{{ asset('images/logo.png') }}" alt="Rabby TCG Logo" class="h-14 md:h-24 w-auto object-contain relative z-10 transition-transform duration-500 group-hover:scale-105 drop-shadow-[0_0_10px_rgba(230,46,107,0.4)]">
                <div class="absolute -inset-2 bg-primary/10 blur-xl rounded-full scale-0 group-hover:scale-110 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden xl:flex items-center gap-1">
                @php
                $navItems = [
                ['route' => 'home', 'icon' => 'home', 'label' => __('home')],
                ['route' => 'products.index', 'icon' => 'shopping_bag', 'label' => __('products')],
                ['route' => 'news.index', 'icon' => 'newspaper', 'label' => __('news')],
                ];

                foreach (($headerPages ?? []) as $page) {
                $navItems[] = [
                'url' => route('pages.show', $page->slug),
                'icon' => $page->slug === \App\Models\Page::SLUG_CONTACT ? 'mail' : 'info',
                'label' => $page->title,
                'active' => request()->routeIs('pages.show') && request()->route('slug') === $page->slug,
                ];
                }
                @endphp

                @foreach($navItems as $item)
                <a class="nav-link font-bold flex items-center gap-2 text-text-secondary hover:text-text-primary px-3 py-2 rounded-lg transition-all {{ ($item['active'] ?? (isset($item['route']) && request()->routeIs($item['route']))) ? 'active text-primary bg-white/5' : '' }}" href="{{ $item['url'] ?? route($item['route']) }}">
                    <span class="material-icons text-base">{{ $item['icon'] }}</span>
                    <span class="text-[10px] tracking-widest uppercase">{{ $item['label'] }}</span>
                </a>
                @endforeach
            </nav>
        </div>

        <!-- Center TCG Search Input -->
        <div class="hidden md:flex flex-1 max-w-md relative">
            <form action="{{ route('products.index') }}" method="GET" class="w-full relative">
                <input type="text" name="search" placeholder="{{ __('placeholder_search_global') }}" class="w-full bg-white/5 border border-white/10 focus:border-primary/50 rounded-full px-5 py-2 text-xs text-white focus:outline-none transition-all placeholder-white/30 pl-10">
                <span class="material-icons text-white/30 text-sm absolute left-3.5 top-1/2 -translate-y-1/2">search</span>
            </form>
        </div>

        <div class="flex items-center gap-2 md:gap-3 shrink-0">
            <!-- Mobile Search Button -->
            <button id="mobile-search-btn" type="button" aria-expanded="false" aria-controls="mobile-search" class="md:hidden text-white p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all border border-white/10 relative z-[70]">
                <span class="material-icons text-xl">search</span>
            </button>

            <!-- Cart Icon Button -->
            <a href="{{ route('products.index') }}" class="relative flex p-2 rounded-lg bg-white/5 hover:bg-white/10 text-white transition-all border border-white/5 hover:border-primary/30 items-center justify-center" title="Shopping Cart">
                <span class="material-icons text-xl">shopping_cart</span>
                <span class="absolute -top-1.5 -right-1.5 bg-primary text-white text-[9px] font-black w-4.5 h-4.5 rounded-full flex items-center justify-center shadow-[0_0_10px_rgba(230,46,107,0.5)]">0</span>
            </a>

            @auth
            <!-- User Profile -->
            <a href="{{ route('user.profile') }}" class="hidden sm:flex items-center gap-1 md:gap-2 bg-bg-card hover:bg-white/5 px-2 md:px-4 py-1 md:py-2 rounded-lg border border-border cursor-pointer transition-all hover:border-primary/50">
                <span class="material-icons text-primary text-xl md:text-2xl">account_circle</span>
                <div class="flex flex-col">
                    <span class="text-text-primary text-[10px] md:text-[12px] font-bold max-w-[40px] md:max-w-none truncate">{{ auth()->user()->name }}</span>
                    <span class="text-[8px] md:text-[10px] text-text-muted font-black tracking-tighter">ID: {{ auth()->user()->id }}</span>
                </div>
            </a>

            <!-- Logout Button (Desktop) -->
            <form action="{{ route('logout') }}" method="POST" class="hidden md:block">
                @csrf
                <button type="submit" class="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-white transition-all border border-white/10 hover:border-white/30" title="{{ __('logout') }}">
                    <span class="material-icons text-xl">logout</span>
                </button>
            </form>
            @else
            <!-- Login Button -->
            <a href="{{ route('login') }}" class="hidden sm:flex items-center gap-1.5 md:gap-2 btn-esport px-3 md:px-5 py-1.5 md:py-2 rounded-lg font-black transition-all shadow-lg group text-[10px] md:text-xs">
                <span class="material-icons group-hover:rotate-12 transition-transform text-sm md:text-base">login</span>
                <span class="tracking-widest whitespace-nowrap uppercase">{{ __('login') }}</span>
            </a>
            @endauth

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" type="button" aria-expanded="false" aria-controls="mobile-menu" class="lg:hidden text-white p-2 rounded-lg bg-white/15 hover:bg-white/25 transition-all border border-white/20 relative z-[70]">
                <span class="material-icons text-2xl">menu</span>
            </button>
        </div>
    </div>

    <!-- Mobile Search -->
    <div id="mobile-search" class="hidden md:hidden absolute top-full inset-x-0 z-[66] border-t border-white/5 bg-neutral-950/95 backdrop-blur-xl shadow-2xl">
        <div class="container mx-auto px-4 py-4">
            <form action="{{ route('products.index') }}" method="GET" class="relative">
                <input type="text" name="search" placeholder="{{ __('placeholder_search_global') }}" class="w-full bg-white/5 border border-white/10 focus:border-primary/50 rounded-2xl px-5 py-3 text-sm text-white focus:outline-none transition-all placeholder-white/30 pl-11">
                <span class="material-icons text-white/30 text-base absolute left-4 top-1/2 -translate-y-1/2">search</span>
            </form>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden absolute top-full inset-x-0 z-[65] border-t border-white/5 bg-neutral-950/95 backdrop-blur-xl shadow-2xl">
        <nav class="container mx-auto px-4 py-4 flex flex-col gap-1">
            @auth
            <!-- Mobile User Info -->
            <div class="p-4 mb-3 rounded-2xl bg-white/5 border border-white/10 shadow-2xl relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-primary/10 rounded-full blur-2xl"></div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-12 h-12 rounded-full bg-neutral-900 flex items-center justify-center border border-white/10 shadow-inner">
                        <span class="material-icons text-primary text-3xl">account_circle</span>
                    </div>
                    <div>
                        <div class="font-black text-white text-lg tracking-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-neutral-600 font-bold uppercase tracking-wider">ID: {{ auth()->user()->id }}</div>
                    </div>
                </div>
            </div>
            @endauth

            @foreach($navItems as $item)
            <a class="flex items-center gap-3 text-white/90 hover:text-white hover:bg-white/10 font-semibold py-3 px-4 rounded-lg transition-all" href="{{ $item['url'] ?? route($item['route']) }}">
                <span class="material-icons text-xl">{{ $item['icon'] }}</span>
                <span class="tracking-wide capitalize">{{ $item['label'] }}</span>
                <span class="material-icons ml-auto text-white/40">chevron_right</span>
            </a>
            @endforeach

            @guest
            <a class="flex items-center gap-3 text-white/90 hover:text-white hover:bg-white/10 font-semibold py-3 px-4 rounded-lg transition-all sm:hidden" href="{{ route('login') }}">
                <span class="material-icons text-xl">login</span>
                <span class="tracking-wide capitalize">{{ __('login') }}</span>
                <span class="material-icons ml-auto text-white/40">chevron_right</span>
            </a>
            @endguest

            @auth
            <a class="flex items-center gap-3 text-white/90 hover:text-white hover:bg-white/10 font-semibold py-3 px-4 rounded-lg transition-all" href="{{ route('user.profile') }}">
                <span class="material-icons text-xl">account_circle</span>
                <span class="tracking-wide capitalize">{{ __('profile') }}</span>
                <span class="material-icons ml-auto text-white/40">chevron_right</span>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 text-red-200 hover:text-red-100 hover:bg-white/10 font-semibold py-3 px-4 rounded-lg transition-all">
                    <span class="material-icons text-xl">logout</span>
                    <span class="tracking-wide capitalize">{{ __('logout') }}</span>
                    <span class="material-icons ml-auto text-white/40">chevron_right</span>
                </button>
            </form>
            @endauth
        </nav>
    </div>
    <!-- Header Waves removed for dark neon theme -->
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileSearchBtn = document.getElementById('mobile-search-btn');
        const mobileSearch = document.getElementById('mobile-search');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const taglineItems = Array.from(document.querySelectorAll('[data-tagline-item]'));
        let activeTaglineIndex = 0;

        function showTaglineItem(index) {
            taglineItems.forEach((item, itemIndex) => {
                item.classList.toggle('is-active', itemIndex === index);
            });
        }

        if (mobileSearchBtn && mobileSearch) {
            mobileSearchBtn.addEventListener('click', function() {
                const willExpand = mobileSearch.classList.contains('hidden');

                mobileSearch.classList.toggle('hidden');
                mobileSearchBtn.setAttribute('aria-expanded', willExpand ? 'true' : 'false');

                if (willExpand) {
                    mobileMenu.classList.add('hidden');
                    mobileMenuBtn?.setAttribute('aria-expanded', 'false');
                }
            });
        }

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileSearch.classList.add('hidden');
                mobileSearchBtn?.setAttribute('aria-expanded', 'false');

                mobileMenu.classList.toggle('hidden');
                const isExpanded = !mobileMenu.classList.contains('hidden');
                mobileMenuBtn.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');

                if (isExpanded) {
                    const menuItems = mobileMenu.querySelectorAll('a');
                    menuItems.forEach((item) => {
                        item.style.transition = 'all 0.2s ease-out';
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    });
                }
            });

            document.addEventListener('click', function(event) {
                const clickedOutsideMenu = !mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target);
                const clickedOutsideSearch = !mobileSearchBtn.contains(event.target) && !mobileSearch.contains(event.target);

                if (clickedOutsideMenu) {
                    mobileMenu.classList.add('hidden');
                    mobileMenuBtn.setAttribute('aria-expanded', 'false');
                }

                if (clickedOutsideSearch) {
                    mobileSearch.classList.add('hidden');
                    mobileSearchBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        if (taglineItems.length > 0) {
            showTaglineItem(activeTaglineIndex);

            if (taglineItems.length > 1) {
                setInterval(() => {
                    activeTaglineIndex = (activeTaglineIndex + 1) % taglineItems.length;
                    showTaglineItem(activeTaglineIndex);
                }, 3200);
            }
        }
    });
</script>

<style>
    body {
        overflow-x: hidden;
    }

    .nav-link.active {
        background: rgba(255, 255, 255, 0.05);
        color: var(--color-primary) !important;
    }

    .header-tagline-stage {
        position: relative;
        height: 16px;
        overflow: hidden;
        width: 100%;
    }

    @keyframes headerTaglineShot {
        0% {
            opacity: 0;
            transform: translate3d(100%, 0, 0);
        }

        12% {
            opacity: 1;
            transform: translate3d(30%, 0, 0);
        }

        40% {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }

        72% {
            opacity: 1;
            transform: translate3d(-30%, 0, 0);
        }

        100% {
            opacity: 0;
            transform: translate3d(-100%, 0, 0);
        }
    }

    .header-tagline-item {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        opacity: 0;
        pointer-events: none;
        will-change: transform, opacity;
        transform: translate3d(100%, 0, 0);
    }

    .header-tagline-item.is-active {
        animation: headerTaglineShot 5s ease-in-out forwards;
    }
</style>
