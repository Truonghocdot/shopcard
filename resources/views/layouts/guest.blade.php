<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Rabby TCG - Graded Cards & Premium Collectibles')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'Rabby TCG is the premier store for authentic graded trading card games, Pokemon cards, and premium collectibles.')">
    <meta name="keywords" content="@yield('keywords', 'Rabby TCG, graded cards, PSA cards, trading card game, Pokemon cards, Weiss Schwarz, One Piece card game')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Open Graph & Twitter Card -->
    <meta property="og:title" content="@yield('title', 'Rabby TCG - Graded Cards & Premium Collectibles')">
    <meta property="og:description" content="@yield('description', 'Rabby TCG is the premier store for authentic graded trading card games, Pokemon cards, and premium collectibles.')">
    <meta property="og:type" content="@yield('og:type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og:image', asset('images/og-image.png'))">
    <meta property="og:site_name" content="Rabby TCG">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Rabby TCG - Graded Cards & Premium Collectibles')">
    <meta name="twitter:description" content="@yield('description', 'Rabby TCG is the premier store for authentic graded trading card games, Pokemon cards, and premium collectibles.')">
    <meta name="twitter:image" content="@yield('og:image', asset('images/og-image.png'))">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&family=Lexend:wght@300;400;500;600;700&family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    @stack('schema')
</head>

<body class="min-h-screen bg-black">

    <!-- Header -->
    @include('components.header')

    <!-- Main Content -->
    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 right-6 flex flex-col gap-3 z-50">
        <a href="#" class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition">
            <span class="material-icons">message</span>
        </a>
        <a href="#" class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition">
            <span class="material-icons">phone</span>
        </a>
    </div>

    @stack('scripts')
</body>

</html>