<div class="hidden md:flex items-center gap-2 mr-2">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.language-switcher', [
        'locales' => config('locales.supported', ['en' => 'English']),
        'currentLocale' => app()->getLocale(),
        'wrapperClass' => 'flex items-center gap-2',
        'buttonClass' => 'w-8 h-8 p-1',
    ])
</div>
