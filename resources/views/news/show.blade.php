@extends('layouts.app')

@section('title', ($news->meta_title ?? $news->title) . ' - Rabby TCG')
@section('description', $news->meta_description ?? $news->description)

<style>
    .news-content {
        line-height: 1.8;
        color: var(--color-text-secondary);
    }

    .news-content img {
        max-width: 100%;
        height: auto;
        border-radius: 1rem;
        margin: 2rem 0;
        border: 1px solid var(--color-border);
        box-shadow: 0 0 30px rgba(0,0,0,0.3);
    }

    .news-content h2,
    .news-content h3 {
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
        font-weight: 900;
        color: var(--color-text-primary);
        text-transform: uppercase;
        letter-spacing: -0.025em;
    }

    .news-content p {
        margin-bottom: 1.5rem;
    }
</style>

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="w-full mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-6 text-[10px] font-black text-text-muted flex items-center gap-2 uppercase tracking-[0.2em]">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1.5">
                <span class="material-icons text-sm mr-1">home</span> {{ __('home') }}
            </a>
            <span class="mx-1 text-white/10">/</span>
            <a href="{{ route('news.index') }}" class="hover:text-primary transition-colors">{{ __('news') }}</a>
            <span class="mx-1 text-white/10">/</span>
            <span class="text-primary drop-shadow-[0_0_8px_rgba(230,46,107,0.4)]">{{ $news->title }}</span>
        </div>

        <!-- Article -->
        <article class="bg-bg-card rounded-2xl overflow-hidden border border-border shadow-3xl mb-12 relative">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary/5 blur-[120px] rounded-full pointer-events-none"></div>
            
            @if($news->thumbnail)
            <div class="aspect-video w-full overflow-hidden relative">
                <img alt="{{ $news->title }}" class="w-full h-full object-cover" src="{{ url('storage/'.$news->thumbnail) }}" loading="lazy">
                <div class="absolute inset-0 bg-linear-to-t from-bg-card via-transparent to-transparent"></div>
            </div>
            @endif

            <div class="p-6 md:p-12 relative z-10">
                <h1 class="text-3xl md:text-5xl font-black mb-6 text-text-primary leading-tight tracking-tight flex items-center gap-3 md:gap-4 relative">
                    <span class="material-icons text-primary">newspaper</span>
                    {{ $news->title }}
                </h1>
                <div class="flex items-center gap-6 text-[10px] font-black text-text-muted mb-8 pb-8 border-b border-white/5 uppercase tracking-widest">
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-sm text-primary">schedule</span>
                        {{ $news->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-sm text-primary">visibility</span>
                        {{ number_format($news->view_count) }} {{ __('views_count') }}
                    </div>
                </div>

                @if($news->description)
                <div class="text-lg text-text-secondary mb-8 italic border-l-4 border-primary pl-6 bg-bg-dark/50 p-6 rounded-2xl border">
                    {!! $news->description !!}
                </div>
                @endif

                <div class="news-content">
                    {!! $news->content !!}
                </div>
            </div>
        </article>

        <!-- Related News -->
        @if($relatedNews->count() > 0)
        <div class="mb-12">
            <h2 class="text-2xl font-black mb-8 text-text-primary uppercase tracking-tight flex items-center gap-3">
                <span class="w-8 h-1 bg-primary rounded-full"></span>
                {{ __('related_news') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedNews as $item)
                 <article class="card-esport group transition-all hover:scale-[1.02] relative">
                     <div class="relative overflow-hidden aspect-video min-h-[140px] md:min-h-0">
                         <img alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" src="{{ url('storage/'.$item->thumbnail) ?? 'https://via.placeholder.com/400x225' }}" loading="lazy">
                     </div>
                     <div class="p-5">
                         <h3 class="font-bold text-base mb-3 line-clamp-2 h-12 text-text-primary group-hover:text-primary transition-colors tracking-tight leading-tight">{{ $item->title }}</h3>
                         <div class="flex items-center gap-2 text-[10px] text-text-muted mb-4 font-black uppercase tracking-widest">
                             <span class="material-icons text-xs text-primary">schedule</span>
                             {{ $item->created_at->diffForHumans() }}
                         </div>
                         <a href="{{ route('news.show', $item->slug) }}" class="text-text-primary hover:text-primary font-black text-xs uppercase tracking-widest flex items-center gap-2 transition-colors group/link border-t border-white/5 pt-4">
                             {{ __('read_more') }} <span class="material-icons text-sm group-hover/link:translate-x-1 transition-transform">arrow_forward</span>
                         </a>
                     </div>
                 </article>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@endsection