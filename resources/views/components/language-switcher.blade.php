@php
    $locales = $locales ?? config('locales.supported', ['en' => 'English']);
    $currentLocale = $currentLocale ?? app()->getLocale();
@endphp

<div class="{{ $wrapperClass ?? 'flex items-center gap-2' }}">
    @foreach($locales as $localeCode => $localeLabel)
        <form action="{{ route('locale.switch', $localeCode) }}" method="POST">
            @csrf
            <button
                type="submit"
                class="flex items-center justify-center rounded-full border transition-all {{ $currentLocale === $localeCode ? 'border-primary bg-white/10 shadow-[0_0_12px_rgba(230,46,107,0.25)]' : 'border-white/10 bg-white/5 hover:border-primary/40 hover:bg-white/10' }} {{ $buttonClass ?? 'w-9 h-9 p-1.5' }}"
                aria-label="{{ $localeLabel }}"
                title="{{ $localeLabel }}"
            >
                <img
                    src="{{ asset("images/lang/{$localeCode}.svg") }}"
                    alt="{{ $localeLabel }}"
                    class="w-full h-full rounded-full object-cover"
                    loading="lazy"
                    decoding="async"
                >
            </button>
        </form>
    @endforeach
</div>
