@extends('layouts.app')

@section('title', __('sign_up_title'))
@section('description', __('sign_up_desc'))

@section('content')
<div class="min-h-[calc(100vh-250px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative background glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl h-full max-h-3xl bg-primary/5 blur-[150px] rounded-full pointer-events-none"></div>

    <div class="max-w-md w-full space-y-8 bg-bg-card p-8 md:p-12 rounded-[2.5rem] shadow-3xl border border-border relative z-10 group">
        <!-- Inner glow effect on group hover -->
        <div class="absolute -inset-1 bg-linear-to-r from-primary/20 to-indigo-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none rounded-[2.5rem]"></div>

        <div class="text-center relative">
            <h1 class="text-3xl font-black text-text-primary uppercase tracking-tighter mb-2 italic">
                {{ __('create') }} <span class="text-primary drop-shadow-[0_0_15px_rgba(34,197,94,0.4)]">{{ __('account') }}</span>
            </h1>
            <p class="text-xs font-bold text-text-muted uppercase tracking-widest">
                {{ __('already_have_account') }}
                <a href="{{ route('login') }}" class="text-primary hover:text-text-primary transition-colors underline underline-offset-4 decoration-primary/30">
                    {{ __('sign_in_now') }}
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6 relative" action="{{ route('register') }}" method="POST" x-data="{ showPassword: false }">
            @csrf

            <div class="space-y-4">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-[10px] font-black text-text-muted mb-2 ml-4 uppercase tracking-[0.2em]">{{ __('username_label') }}</label>
                    <div class="relative group/field">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-600 transition-colors group-hover/field:text-primary">
                            <span class="material-icons text-sm">person</span>
                        </span>
                        <input id="username" name="username" type="text" autocomplete="username" required
                            class="appearance-none rounded-2xl relative block w-full pl-11 pr-4 py-4 bg-bg-dark/80 border border-border placeholder-text-muted text-text-primary focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary sm:text-sm font-bold tracking-tight transition-all shadow-inner"
                            placeholder="{{ __('username_placeholder') }}" value="{{ old('username') }}">
                    </div>
                    @error('username')
                    <p class="mt-2 ml-4 text-[10px] font-black text-pink-500 uppercase tracking-widest">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-[10px] font-black text-text-muted mb-2 ml-4 uppercase tracking-[0.2em]">{{ __('password_label') }}</label>
                    <div class="relative group/field">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-600 transition-colors group-hover/field:text-primary">
                            <span class="material-icons text-sm">lock</span>
                        </span>
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" autocomplete="new-password" required
                            class="appearance-none rounded-2xl relative block w-full pl-11 pr-12 py-4 bg-bg-dark/80 border border-border placeholder-text-muted text-text-primary focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary sm:text-sm font-bold tracking-tight transition-all shadow-inner"
                            placeholder="{{ __('password_placeholder') }}">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-600 hover:text-white transition-colors cursor-pointer outline-none">
                            <span class="material-icons text-sm" x-show="!showPassword">visibility</span>
                            <span class="material-icons text-sm" x-show="showPassword" x-cloak>visibility_off</span>
                        </button>
                    </div>
                    @error('password')
                    <p class="mt-2 ml-4 text-[10px] font-black text-pink-500 uppercase tracking-widest">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-[10px] font-black text-text-muted mb-2 ml-4 uppercase tracking-[0.2em]">{{ __('confirm_password_label') }}</label>
                    <div class="relative group/field">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-600 transition-colors group-hover/field:text-primary">
                            <span class="material-icons text-sm">lock_clock</span>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" :type="showPassword ? 'text' : 'password'" autocomplete="new-password" required
                            class="appearance-none rounded-2xl relative block w-full pl-11 pr-4 py-4 bg-bg-dark/80 border border-border placeholder-text-muted text-text-primary focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary sm:text-sm font-bold tracking-tight transition-all shadow-inner"
                            placeholder="{{ __('confirm_password_placeholder') }}">
                    </div>
                </div>

                <!-- Referrer ID -->
                <div>
                    <label for="referrer_id" class="block text-[10px] font-black text-text-muted mb-2 ml-4 uppercase tracking-[0.2em]">{{ __('referral_code_label') }}</label>
                    <div class="relative group/field">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-600 transition-colors group-hover/field:text-primary">
                            <span class="material-icons text-sm">group_add</span>
                        </span>
                        <input id="referrer_id" name="referrer_id" type="text"
                            class="appearance-none rounded-2xl relative block w-full pl-11 pr-4 py-4 bg-bg-dark/80 border border-border placeholder-text-muted text-text-primary focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary sm:text-sm font-bold tracking-tight transition-all shadow-inner"
                            placeholder="{{ __('referral_code_placeholder') }}"
                            value="{{ request('ref') ?? old('referrer_id') }}">
                    </div>
                    <p class="mt-2 ml-4 text-[9px] font-bold text-text-muted uppercase tracking-widest leading-relaxed">{{ __('referral_code_hint') }}</p>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="group relative w-full flex justify-center py-5 px-4 border-none text-xs font-black rounded-2xl text-white btn-esport shadow-2xl shadow-primary/20 active:scale-95 transition-all overflow-hidden uppercase tracking-[0.2em]">
                    <span class="flex items-center gap-2 relative z-10 transition-all group-hover:gap-3">
                        <span class="material-icons text-sm">person_add</span>
                        {{ __('register_account_btn') }}
                    </span>
                    <div class="absolute inset-y-0 left-0 w-12 bg-linear-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-[400px] transition-transform duration-1000 ease-in-out"></div>
                </button>
            </div>

            <p class="text-[9px] text-text-muted font-bold text-center mt-6 leading-relaxed uppercase tracking-widest max-w-[280px] mx-auto opacity-60">
                {!! __('register_agreement', ['terms_link' => route('policy'), 'privacy_link' => route('policy')]) !!}
            </p>
        </form>
    </div>
</div>
@endsection