@extends('layouts.app')

@section('title', __('profile_meta_title'))
@section('description', __('profile_meta_desc'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8" x-data="{ activeTab: 'info' }">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['name' => __('home'), 'url' => route('home')],
        ['name' => __('profile'), 'url' => route('user.profile')]
    ]" />

    @if(session('success'))
    <div class="mb-6 px-6 text-[10px] md:text-xs">
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center gap-3 font-black uppercase tracking-widest shadow-lg shadow-emerald-500/5">
            <span class="material-icons text-sm">check_circle</span>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 px-6 text-[10px] md:text-xs">
        <div class="p-4 bg-pink-500/10 border border-pink-500/20 text-pink-500 rounded-xl font-black uppercase tracking-widest shadow-lg shadow-pink-500/5">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                <li class="flex items-center gap-2"><span class="w-1 h-1 bg-pink-500 rounded-full"></span> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <aside class="w-full lg:w-72 shrink-0">
            <div class="flex flex-col gap-6 sticky top-24">
                <div class="bg-bg-card rounded-2xl border border-border shadow-2xl p-6 relative overflow-hidden">
                    <!-- Background glow -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/10 blur-[50px] rounded-full pointer-events-none"></div>

                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <div class="shrink-0 aspect-square rounded-2xl size-14 border-2 border-primary shadow-[0_0_15px_rgba(34,197,94,0.4)] overflow-hidden bg-bg-dark">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCVGaTwIrah77vJVAoTY2oc_aYDyOz5LSOrCGtbT5xJGT8vhdJvPOeQhIfBXNGNP1OqlZ6rjdWRwY4Mvx_HLW1et0PwzS_48fJA9OLtGrnJjhVdHO7LwLY3lHSfwiMXSZiJFKYF7iMtWE5zyEJMiCP8WTJyqpKenn1bOSaBIENdAPC8fapysJ-DAqblpdj0C_bv17YfZMqWv_n4NyPWgJumLLYNtr7AUfCnVI5C_5JWL09YXcVEfripuVOhgYaLq2aWga_ajQXo9m-e" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-text-primary text-lg font-black tracking-tighter truncate w-40">{{ Auth::user()->name }}</h1>
                            <p class="text-primary text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5 mt-1">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
                                {{ __('member') }}
                            </p>
                        </div>
                    </div>

                    <nav class="flex flex-col gap-3 relative z-10">
                        <button @click="activeTab = 'info'"
                            :class="activeTab === 'info' ? 'bg-primary/10 text-primary border-primary/20 shadow-[0_0_15px_rgba(34,197,94,0.1)]' : 'text-text-muted hover:text-text-primary hover:bg-white/5 border-transparent'"
                            class="flex items-center gap-4 px-4 py-3.5 rounded-2xl border transition-all text-left w-full group">
                            <span class="material-icons text-[20px] transition-transform group-hover:scale-110">person</span>
                            <p class="text-[11px] font-black uppercase tracking-widest">{{ __('account_information') }}</p>
                        </button>
                        <button @click="activeTab = 'orders'"
                            :class="activeTab === 'orders' ? 'bg-primary/10 text-primary border-primary/20 shadow-[0_0_15px_rgba(34,197,94,0.1)]' : 'text-text-muted hover:text-text-primary hover:bg-white/5 border-transparent'"
                            class="flex items-center gap-4 px-4 py-3.5 rounded-2xl border transition-all text-left w-full group">
                            <span class="material-icons text-[20px] transition-transform group-hover:scale-110">shopping_bag</span>
                            <p class="text-[11px] font-black uppercase tracking-widest">{{ __('purchased_cards') }}</p>
                        </button>
                    </nav>

                    <div class="mt-8 pt-8 border-t border-border flex flex-col gap-3 relative z-10">
                        <a href="{{ route('deposit') }}" class="w-full flex cursor-pointer items-center justify-center rounded-2xl py-4 btn-esport text-[11px] font-black uppercase tracking-widest shadow-primary/20 border-none active:scale-95 transition-all">
                            <span class="material-icons mr-2 text-[20px]">add_circle</span>
                            <span>{{ __('deposit_now') }}</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex cursor-pointer items-center justify-center rounded-2xl py-4 bg-bg-dark/50 hover:bg-white/5 text-text-muted hover:text-text-primary transition-all text-[11px] font-black uppercase tracking-widest active:scale-95 border border-border">
                                <span class="material-icons mr-2 text-[20px]">logout</span>
                                <span>{{ __('logout') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            <div x-show="activeTab === 'info'" class="space-y-6">

                <livewire:user.profile-info wire:key="profile-info-content" />
            </div>

            <div x-show="activeTab === 'orders'" class="space-y-6" style="display: none;">
                <livewire:user.purchased-products wire:key="purchased-products-content" />
            </div>
        </div>
    </div>
</div>
@endsection
