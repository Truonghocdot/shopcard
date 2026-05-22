@extends('layouts.app')

@section('title', $pageMetaTitle ?: $pageTitle . ' - Rabby TCG')
@section('description', $pageMetaDescription)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <nav class="flex mb-8 text-[10px] font-black text-text-muted uppercase tracking-[0.2em]">
        <a href="{{ route('home') }}" class="hover:text-primary flex items-center transition-colors">
            <span class="material-icons text-sm mr-2">home</span> {{ __('home') }}
        </a>
        <span class="mx-3 text-white/10">/</span>
        <span class="text-primary font-black">{{ $pageTitle }}</span>
    </nav>

    <div class="glass rounded-3xl border border-white/10 p-8 md:p-12 shadow-2xl">
        <h1 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight mb-8 leading-tight">
            {{ $pageTitle }}
        </h1>
        <div class="prose prose-invert prose-sm max-w-none text-neutral-300 leading-relaxed">
            {!! $pageContent !!}
        </div>
    </div>
</div>
@endsection
