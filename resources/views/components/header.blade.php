<!-- Top Info Bar -->
<div class="bg-black py-1 overflow-hidden whitespace-nowrap border-b border-white/5 relative z-60">
    <div class="animate-marquee flex items-center gap-8">
        <span class="text-primary font-black text-[10px] uppercase tracking-[0.2em] flex items-center gap-2">
            {{ $siteSettings['site_tagline'] ?? __('premium_tcg_shop') }}
        </span>
    </div>
</div>

<header class="sticky top-0 z-50 glass border-b border-border shadow-2xl overflow-visible h-[70px] flex items-center">
    <!-- Removed Tet decorative branches and firecrackers -->

    <div class="container mx-auto px-2 md:px-4 py-2 md:py-3 flex items-center justify-between relative z-40 gap-4">
        <div class="flex items-center gap-6 shrink-0">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group relative">
                <img src="{{ asset('images/logo.png') }}" alt="Rabby TCG Logo" class="h-10 md:h-20 w-auto object-contain relative z-10 transition-transform duration-500 group-hover:scale-105 drop-shadow-[0_0_10px_rgba(230,46,107,0.4)]">
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

        <div class="flex items-center gap-3 shrink-0">
            <!-- Cart Icon Button -->
            <a href="{{ route('products.index') }}" class="relative p-2 rounded-lg bg-white/5 hover:bg-white/10 text-white transition-all border border-white/5 hover:border-primary/30 flex items-center justify-center" title="Shopping Cart">
                <span class="material-icons text-xl">shopping_cart</span>
                <span class="absolute -top-1.5 -right-1.5 bg-primary text-white text-[9px] font-black w-4.5 h-4.5 rounded-full flex items-center justify-center shadow-[0_0_10px_rgba(230,46,107,0.5)]">0</span>
            </a>

            @auth
            <!-- User Profile -->
            <a href="{{ route('user.profile') }}" class="flex items-center gap-1 md:gap-2 bg-bg-card hover:bg-white/5 px-2 md:px-4 py-1 md:py-2 rounded-lg border border-border cursor-pointer transition-all hover:border-primary/50">
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
            <a href="{{ route('login') }}" class="flex items-center gap-1.5 md:gap-2 btn-esport px-3 md:px-5 py-1.5 md:py-2 rounded-lg font-black transition-all shadow-lg group text-[10px] md:text-xs">
                <span class="material-icons group-hover:rotate-12 transition-transform text-sm md:text-base">login</span>
                <span class="tracking-widest whitespace-nowrap uppercase">{{ __('login') }}</span>
            </a>
            @endauth

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="lg:hidden text-white p-2 rounded-lg bg-white/15 hover:bg-white/25 transition-all border border-white/20">
                <span class="material-icons text-2xl">menu</span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden border-t border-white/5 bg-neutral-950/95 backdrop-blur-xl">
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
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');

                if (!mobileMenu.classList.contains('hidden')) {
                    const menuItems = mobileMenu.querySelectorAll('a');
                    menuItems.forEach((item, index) => {
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(-20px)';
                        setTimeout(() => {
                            item.style.transition = 'all 0.3s ease-out';
                            item.style.opacity = '1';
                            item.style.transform = 'translateX(0)';
                        }, index * 50);
                    });
                }
            });

            document.addEventListener('click', function(event) {
                if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
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
</style>
