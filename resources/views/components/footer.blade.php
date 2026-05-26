<footer class="relative mt-16 pt-16 pb-8 bg-bg-dark border-t border-border">
    <!-- Decorative background glow -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-primary/5 blur-[100px] rounded-full pointer-events-none"></div>
    <div class="container mx-auto px-2 md:px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- About -->
            <div>
                <div class="flex items-center gap-2 mb-8">
                    <a href="{{ route('home') }}" class="group">
                        <img src="{{ asset('images/logo.png') }}" alt="Rabby TCG Logo" class="h-14 md:h-24 w-auto object-contain drop-shadow-[0_0_15px_rgba(230,46,107,0.35)] transition-opacity group-hover:opacity-80" loading="lazy" decoding="async">
                    </a>
                </div>
                <p class="text-text-secondary text-sm leading-relaxed max-w-sm">
                    {!! $siteSettings['footer_about'] ?? __('footer_about_desc') !!}
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h5 class="font-black text-sm mb-6 text-white uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-2 h-2 bg-primary rounded-full"></span>
                    {{ __('quick_links') }}
                </h5>
                <ul class="space-y-3 text-sm text-text-secondary">
                    <li><a class="hover:text-white transition-colors flex items-center gap-2 group" href="{{ route('home') }}">
                            <span class="material-icons text-xs group-hover:translate-x-1 transition-transform">chevron_right</span>
                            {{ __('home') }}
                        </a></li>
                    <li><a class="hover:text-white transition-colors flex items-center gap-2 group" href="{{ route('products.index') }}">
                            <span class="material-icons text-xs group-hover:translate-x-1 transition-transform">chevron_right</span>
                            {{ __('products') }}
                        </a></li>
                    <li><a class="hover:text-white transition-colors flex items-center gap-2 group" href="{{ route('news.index') }}">
                            <span class="material-icons text-xs group-hover:translate-x-1 transition-transform">chevron_right</span>
                            {{ __('news') }}
                        </a></li>
                    @foreach(($footerPages ?? []) as $page)
                    <li><a class="hover:text-white transition-colors flex items-center gap-2 group" href="{{ route('pages.show', $page->slug) }}">
                            <span class="material-icons text-xs group-hover:translate-x-1 transition-transform">chevron_right</span>
                            {{ $page->title }}
                        </a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h5 class="font-black text-sm mb-6 text-white uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-2 h-2 bg-primary rounded-full"></span>
                    {{ __('customer_support') }}
                </h5>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center border border-white/10 group-hover:border-primary/50 transition-colors">
                            <span class="material-icons text-primary">phone</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-neutral-600 uppercase tracking-widest font-black">Hotline</p>
                            <p class="text-sm font-bold text-white group-hover:text-primary transition-colors">{{ $siteSettings['contact_phone'] ?? '0986.526.036' }}</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center border border-white/10 group-hover:border-primary/50 transition-colors">
                            <span class="material-icons text-primary">schedule</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-neutral-600 uppercase tracking-widest font-black">{{ __('working_hours') }}</p>
                            <p class="text-sm font-bold text-white">{{ $siteSettings['support_hours'] ?? '08:00AM - 22:00PM' }}</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center border border-white/10 group-hover:border-primary/50 transition-colors">
                            <span class="material-icons text-primary">mail</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-neutral-600 uppercase tracking-widest font-black">{{ __('email_address') }}</p>
                            <p class="text-sm font-bold text-white">{{ $siteSettings['contact_email'] ?? 'support@rabbytcg.com' }}</p>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Social -->
            <div>
                <h5 class="font-black text-sm mb-6 text-white uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-2 h-2 bg-primary rounded-full"></span>
                    {{ __('follow_us') }}
                </h5>
                <div class="flex gap-4">
                    @if(!empty($siteSettings['facebook_link']))
                    <a class="w-12 h-12 bg-white/5 hover:bg-primary border border-white/10 text-white rounded-full flex items-center justify-center transition-all hover:scale-110 shadow-lg" href="{{ $siteSettings['facebook_link'] }}" aria-label="Facebook" target="_blank" rel="noopener">
                        <span class="material-icons">facebook</span>
                    </a>
                    @endif
                    @if(!empty($siteSettings['instagram_link']))
                    <a class="w-12 h-12 bg-white/5 hover:bg-primary border border-white/10 text-white rounded-full flex items-center justify-center transition-all hover:scale-110 shadow-lg" href="{{ $siteSettings['instagram_link'] }}" aria-label="Instagram" target="_blank" rel="noopener">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5a4.25 4.25 0 0 0 4.25 4.25h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5a4.25 4.25 0 0 0-4.25-4.25h-8.5Zm8.75 2.25a1 1 0 1 1 0 2 1 1 0 0 1 0-2ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/>
                        </svg>
                    </a>
                    @endif
                    @if(!empty($siteSettings['tiktok_link']))
                    <a class="w-12 h-12 bg-white/5 hover:bg-primary border border-white/10 text-white rounded-full flex items-center justify-center transition-all hover:scale-110 shadow-lg" href="{{ $siteSettings['tiktok_link'] }}" aria-label="Tiktok" target="_blank" rel="noopener">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.28V2h-3.13v13.24c0 1.77-1.41 3.22-3.17 3.27a3.18 3.18 0 0 1-3.3-3.18 3.18 3.18 0 0 1 3.18-3.18c.35 0 .69.06 1 .16V9.12a6.3 6.3 0 0 0-1-.08A6.31 6.31 0 0 0 3 15.35a6.31 6.31 0 0 0 6.52 6.3 6.46 6.46 0 0 0 6.3-6.46V8.47a7.9 7.9 0 0 0 4.77 1.6V6.94c-.34 0-.67-.08-1-.25Z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>

    </div>
</footer>
