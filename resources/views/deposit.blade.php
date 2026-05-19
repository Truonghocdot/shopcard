@extends('layouts.app')

@section('title', __('deposit_meta_title'))
@section('description', __('deposit_meta_desc'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['name' => __('home'), 'url' => route('home')],
        ['name' => __('deposit'), 'url' => route('deposit')]
    ]" />

    <!-- Page Header -->
    <div class="mb-12 text-center relative scroll-reveal">
        <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter text-white mb-4 flex justify-center items-center gap-4 relative z-10">
            <span class="material-icons text-5xl md:text-6xl text-primary drop-shadow-[0_0_15px_rgba(74,222,128,0.5)]">account_balance_wallet</span>
            {{ __('deposit_automated') }}
        </h1>
        <p class="text-neutral-600 font-black uppercase tracking-[0.3em] text-[10px] md:text-xs">Fast Deposit • Auto Approval • High Security</p>
        <div class="h-1 w-64 bg-linear-to-r from-transparent via-primary/50 to-transparent mx-auto rounded-full mt-8"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-5 space-y-6">
            <div class="glass rounded-3xl overflow-hidden border border-white/10 shadow-2xl relative group h-full">
                <!-- Header -->
                <div class="bg-linear-to-r from-primary to-indigo-600 p-6 flex justify-between items-center relative z-10">
                    <span class="font-black uppercase tracking-widest flex items-center gap-3 text-white text-sm">
                        <span class="material-icons">credit_card</span> {{ __('transfer_details') }}
                    </span>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse shadow-[0_0_8px_#4ade80]"></div>
                        <span class="text-[9px] text-white/90 font-black uppercase tracking-widest">{{ __('system_online') }}</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-neutral-600 text-[10px] uppercase font-black mb-2 tracking-[0.2em]">{{ __('bank') }}</p>
                                <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-4">
                                    <p class="text-xl font-black text-primary italic drop-shadow-[0_0_8px_rgba(74,222,128,0.3)]">{{ $banking }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-neutral-600 text-[10px] uppercase font-black mb-2 tracking-[0.2em]">{{ __('account_holder') }}</p>
                                <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-4">
                                    <p class="text-base font-black text-white uppercase tracking-tight">{{ $bankName }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="relative z-10">
                            <p class="text-neutral-600 text-[10px] uppercase font-black mb-2 tracking-[0.2em]">{{ __('account_number') }}</p>
                            <div class="flex items-center gap-3">
                                <div class="bg-white/5 border-2 border-primary/20 hover:border-primary/50 transition-all px-5 py-4 rounded-2xl flex-1 group/acc">
                                    <span class="text-3xl font-mono font-black text-primary tracking-tighter drop-shadow-[0_0_10px_rgba(74,222,128,0.4)]">{{ $bankNumber }}</span>
                                </div>
                                <button class="btn-esport h-full px-6 py-4 rounded-2xl transition-all active:scale-95 flex items-center justify-center shrink-0 border-none" onclick="navigator.clipboard.writeText('{{ $bankNumber }}')">
                                    <span class="material-icons">content_copy</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="flex justify-center pt-8 mt-8 border-t border-white/5 relative z-10">
                        <div class="bg-neutral-950 p-6 rounded-[2.5rem] border border-white/10 shadow-2xl flex flex-col items-center relative active:scale-[1.05] transition-transform duration-300">
                            <div class="p-3 bg-white rounded-3xl shadow-[0_0_30px_rgba(74,222,128,0.2)]">
                                <img alt="QR Code Bank Transfer" class="w-56 h-56 rounded-2xl" src="https://api.vietqr.io/image/{{ $bankBin }}-{{ $bankNumber }}-compact2.png?amount=0&addInfo=vanhfco%20{{ Auth::id() }}&accountName={{ urlencode($bankName) }}">
                            </div>
                            <div class="mt-6 text-center">
                                <div class="inline-block bg-primary/10 text-primary text-[9px] font-black uppercase tracking-[0.3em] px-4 py-1.5 rounded-full mb-3 border border-primary/20">VietQR 2.0</div>
                                <p class="text-lg text-white font-black uppercase tracking-tight">{{ __('scan_qr_to_deposit') }}</p>
                                <p class="text-[10px] text-primary font-black mt-1 uppercase tracking-widest opacity-80 animate-pulse">{{ __('processed_auto_247') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Warning -->
                    <div class="mt-8 p-5 rounded-2xl bg-primary/5 border border-primary/20 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-primary shadow-[0_0_10px_rgba(74,222,128,0.5)]"></div>
                        <p class="text-xs text-neutral-300 leading-relaxed font-bold flex items-start gap-3">
                            <span class="material-icons text-primary text-base shrink-0">info</span>
                            <span>{{ __('deposit_warning') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="lg:col-span-7">
            <div class="glass rounded-3xl overflow-hidden h-full flex flex-col border border-white/10 shadow-2xl relative">
                <!-- Header -->
                <div class="bg-linear-to-r from-primary to-indigo-600 p-6 relative z-10">
                    <span class="font-black uppercase tracking-widest flex items-center gap-3 text-white text-sm">
                        <span class="material-icons">help_outline</span> {{ __('deposit_steps_title') }}
                    </span>
                </div>

                <!-- Content -->
                <div class="p-8 flex-1 space-y-10">
                    <!-- Transfer Content Section -->
                    <div class="space-y-4">
                        <h3 class="text-[10px] font-black uppercase text-neutral-600 tracking-[0.2em] flex items-center gap-2">
                            <span class="material-icons text-primary text-sm">vpn_key</span>
                            {{ __('required_transfer_content') }}
                        </h3>
                        <div class="bg-indigo-600/10 border border-primary/30 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden group/content shadow-inner">
                            <div class="flex items-center gap-6 relative z-10 text-center md:text-left">
                                <div class="w-16 h-16 rounded-2xl bg-primary flex items-center justify-center text-white shadow-[0_0_20px_rgba(74,222,128,0.4)]">
                                    <span class="material-icons text-4xl">vpn_key</span>
                                </div>
                                <div>
                                    <p class="text-[9px] text-primary font-black uppercase tracking-[0.3em] mb-1">{{ __('exact_syntax') }}</p>
                                    <p class="text-4xl font-black text-white italic tracking-tight drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]">vanhfco {{ Auth::id() }}</p>
                                </div>
                            </div>
                            <button class="btn-esport px-10 py-5 rounded-2xl flex items-center gap-3 font-black text-base shadow-2xl transition-all active:scale-95 relative z-10 border-none" onclick="navigator.clipboard.writeText('vanhfco {{ Auth::id() }}')">
                                <span class="material-icons">content_copy</span> {{ __('copy') }}
                            </button>
                        </div>
                    </div>

                    <!-- Steps -->
                    <div class="space-y-8">
                        <div class="flex gap-5 group">
                            <div class="shrink-0 w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center font-black text-xl text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-lg">1</div>
                            <div class="flex-1 pt-1">
                                <p class="text-neutral-400 text-sm leading-relaxed">{{ __('deposit_step1_desc') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-5 group">
                            <div class="shrink-0 w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center font-black text-xl text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-lg">2</div>
                            <div class="flex-1 pt-1">
                                <p class="text-neutral-400 text-sm leading-relaxed">{{ __('deposit_step2_desc') }} <strong class="text-primary font-black underline decoration-2 underline-offset-4">vanhfco {{ Auth::id() }}</strong>.</p>
                            </div>
                        </div>

                        <!-- Important Notice -->
                        <div class="bg-linear-to-br from-neutral-950 to-indigo-950 rounded-3xl p-8 space-y-5 shadow-2xl relative overflow-hidden group/notice border border-primary/20">
                            <!-- Background glow -->
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>

                            <p class="text-primary font-black text-center text-lg uppercase tracking-[0.4em] relative z-10">{{ __('important_note') }}</p>
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl py-5 px-6 relative z-10 border border-white/5 text-center">
                                <p class="text-white font-black text-xl mb-1">{{ $bankNumber }} <span class="text-neutral-600 font-normal mx-2">|</span> {{ $banking }}</p>
                                <p class="text-neutral-400 text-xs font-bold uppercase tracking-widest">{{ $bankName }}</p>
                            </div>
                            <p class="text-neutral-300 font-bold text-center text-[10px] uppercase tracking-widest leading-relaxed relative z-10">
                                {{ __('correct_content') }}: <span class="text-primary font-black animate-pulse drop-shadow-[0_0_8px_rgba(74,222,128,0.5)]">vanhfco {{ Auth::id() }}</span>
                            </p>
                        </div>

                        <div class="flex gap-5 group">
                            <div class="shrink-0 w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center font-black text-xl text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-lg">3</div>
                            <div class="flex-1 pt-1">
                                <p class="text-neutral-400 text-sm leading-relaxed">{{ __('deposit_step3_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection