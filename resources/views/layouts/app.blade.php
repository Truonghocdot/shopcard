<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="overflow-x-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', ($siteSettings['site_name'] ?? 'Rabby TCG') . ' - Graded Cards & Premium Collectibles')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', $siteSettings['site_description'] ?? 'Rabby TCG is the premier store for authentic graded trading card games, Pokemon cards, and premium collectibles.')">
    <meta name="keywords" content="@yield('keywords', 'Rabby TCG, graded cards, PSA cards, trading card game, Pokemon cards, Weiss Schwarz, One Piece card game')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Open Graph & Twitter Card -->
    <meta property="og:title" content="@yield('title', ($siteSettings['site_name'] ?? 'Rabby TCG') . ' - Graded Cards & Premium Collectibles')">
    <meta property="og:description" content="@yield('description', $siteSettings['site_description'] ?? 'Rabby TCG is the premier store for authentic graded trading card games, Pokemon cards, and premium collectibles.')">
    <meta property="og:type" content="@yield('og:type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og:image', asset('images/og-image.png'))">
    <meta property="og:site_name" content="{{ $siteSettings['site_name'] ?? 'Rabby TCG' }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', ($siteSettings['site_name'] ?? 'Rabby TCG') . ' - Graded Cards & Premium Collectibles')">
    <meta name="twitter:description" content="@yield('description', $siteSettings['site_description'] ?? 'Rabby TCG is the premier store for authentic graded trading card games, Pokemon cards, and premium collectibles.')">
    <meta name="twitter:image" content="@yield('og:image', asset('images/og-image.png'))">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&family=Lexend:wght@300;400;500;600;700&family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @stack('styles')

    <!-- Global Organization Schema -->
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "Organization",
            "name": "{{ $siteSettings['site_name'] ?? 'Rabby TCG' }}",
            "alternateName": ["{{ $siteSettings['site_name'] ?? 'Rabby TCG' }}"],
            "url": "{{ url('/') }}",
            "logo": "{{ asset('images/logo.png') }}",
            "description": "{{ $siteSettings['site_description'] ?? 'Rabby TCG is the premier store for authentic graded trading card games, Pokemon cards, and premium collectibles.' }}",
            "contactPoint": {
                "@@type": "ContactPoint",
                "telephone": "{{ $siteSettings['contact_phone'] ?? '+84986526036' }}",
                "contactType": "Customer Service",
                "availableLanguage": "English"
            },
            "sameAs": [
                "{{ $siteSettings['facebook_link'] ?? 'https://www.facebook.com' }}",
                "{{ $siteSettings['instagram_link'] ?? 'https://www.instagram.com' }}",
                "{{ $siteSettings['zalo_link'] ?? 'https://zalo.me' }}"
            ]
        }
    </script>

</head>

<body class="min-h-screen text-text-primary selection:bg-primary/30 selection:text-white overflow-x-hidden bg-[#080A0F]">
    <!-- Animated background particles/glows -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="sun-glow"></div>
        <div class="fixed top-1/4 -left-32 w-96 h-96 bg-primary/5 rounded-full blur-[120px]"></div>
        <div class="fixed bottom-1/4 -right-32 w-96 h-96 bg-secondary/5 rounded-full blur-[120px]"></div>
    </div>

    <!-- Content Wrapper -->
    <div class="relative z-10">

        <!-- Header -->
        @include('components.header')

        <!-- Order Marquee Banner -->
        @include('components.order-marquee')

        <!-- Main Content -->
        <main class="relative">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <!-- Sea Waves Footer Component -->
        <div class="sea-footer">
            @include('components.footer')
        </div>

        <!-- Floating Action Buttons -->
        <div class="fixed bottom-4 md:bottom-6 right-4 md:right-6 flex flex-col gap-2 md:gap-3 z-50">
            <a href="{{ $siteSettings['facebook_link'] ?? '#' }}" class="w-10 h-10 md:w-12 md:h-12 bg-bg-card border border-primary/20 text-primary rounded-full flex items-center justify-center hover:scale-110 transition shadow-[0_0_15px_rgba(230,46,107,0.3)] hover:bg-primary hover:text-white group">
                <span class="material-icons text-xl md:text-2xl">message</span>
            </a>
            <a href="{{ $siteSettings['instagram_link'] ?? '#' }}" class="w-10 h-10 md:w-12 md:h-12 bg-bg-card border border-primary/20 text-primary rounded-full flex items-center justify-center hover:scale-110 transition shadow-[0_0_15px_rgba(230,46,107,0.3)] hover:bg-primary hover:text-white group">
                <svg class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5a4.25 4.25 0 0 0 4.25 4.25h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5a4.25 4.25 0 0 0-4.25-4.25h-8.5Zm8.75 2.25a1 1 0 1 1 0 2 1 1 0 0 1 0-2ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/>
                </svg>
            </a>
            <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings['contact_phone'] ?? '0327182537') }}" class="w-10 h-10 md:w-12 md:h-12 bg-bg-card border border-primary/20 text-primary rounded-full flex items-center justify-center hover:scale-110 transition shadow-[0_0_15px_rgba(230,46,107,0.3)] hover:bg-primary hover:text-white">
                <span class="material-icons text-xl md:text-2xl">phone</span>
            </a>
            <a href="{{ $siteSettings['zalo_link'] ?? '#' }}" class="w-10 h-10 md:w-12 md:h-12 bg-bg-card border border-primary/20 rounded-full flex items-center justify-center hover:scale-110 transition shadow-[0_0_15px_rgba(230,46,107,0.3)] p-1">
                <img src="{{ asset('images/zalo.png') }}" alt="Zalo" class="w-full h-full rounded-full grayscale hover:grayscale-0 transition-all" loading="lazy" decoding="async">
            </a>
        </div>

    </div> <!-- End Content Wrapper -->

    @livewireScripts
    @stack('scripts')
</body>

</html>
