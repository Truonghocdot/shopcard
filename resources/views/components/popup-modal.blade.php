{{-- Popup Modal Component --}}
@inject('viewDataService', 'App\Services\ViewDataService')

@php
$popupContentResult = $viewDataService->getPopupContent();
$popupContent = $popupContentResult->isSuccess() ? $popupContentResult->getData() : null;
@endphp

@if($popupContent)
<div id="popup-modal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-xl transition-all duration-500">
    <div class="relative max-w-2xl w-full transform transition-all duration-500 scale-95 opacity-0" id="popup-content-wrapper">
        <!-- Modal Content -->
        <div class="glass rounded-[2rem] shadow-[0_0_50px_rgba(0,0,0,0.5)] overflow-hidden border border-white/10">
            <!-- Header -->
            <div class="bg-linear-to-r from-primary to-indigo-600 p-5 flex justify-between items-center">
                <h3 class="text-xl font-black uppercase text-white flex items-center gap-3 tracking-tighter">
                    <span class="material-icons text-white drop-shadow-[0_0_8px_rgba(255,255,255,0.4)]">campaign</span>
                    THÔNG BÁO <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full ml-1">SYSTEM</span>
                </h3>
                <button onclick="closePopup()" class="text-white/80 hover:text-white transition-colors">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <!-- Content -->
            <div class="p-8 max-h-[60vh] overflow-y-auto scrollbar-thin scrollbar-thumb-primary/20 bg-neutral-950/40">
                <div class="prose prose-invert prose-primary max-w-full overflow-hidden break-words">
                    {!! $popupContent !!}
                </div>
            </div>

            <!-- Footer with Duration Options -->
            <div class="bg-neutral-950/50 p-6 border-t border-white/5 backdrop-blur-xl">
                <p class="text-[10px] text-neutral-600 mb-4 font-black uppercase tracking-[0.2em] text-center">Ẩn thông báo này trong</p>
                <div class="flex gap-4">
                    <button onclick="closePopup(1)" class="flex-1 bg-white/5 hover:bg-white/10 border border-white/10 text-neutral-300 px-4 py-3 rounded-xl font-black text-xs transition-all active:scale-95 uppercase tracking-widest flex items-center justify-center gap-2">
                        <span class="material-icons text-sm">schedule</span> 1 GIỜ
                    </button>
                    <button onclick="closePopup(24)" class="flex-1 btn-esport px-4 py-3 rounded-xl font-black text-xs transition-all active:scale-95 uppercase tracking-widest flex items-center justify-center gap-2 border-none">
                        <span class="material-icons text-sm">event</span> 24 GIỜ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function shouldShowPopup() {
        const hideUntil = localStorage.getItem('popup_hide_until');
        if (!hideUntil) return true;
        return new Date() > new Date(hideUntil);
    }

    function closePopup(hours = 0) {
        const modal = document.getElementById('popup-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        if (hours > 0) {
            const hideUntil = new Date();
            hideUntil.setHours(hideUntil.getHours() + hours);
            localStorage.setItem('popup_hide_until', hideUntil.toISOString());
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (shouldShowPopup()) {
            const modal = document.getElementById('popup-modal');
            const wrapper = document.getElementById('popup-content-wrapper');
            setTimeout(() => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                // Trigger animation
                setTimeout(() => {
                    wrapper.classList.remove('scale-95', 'opacity-0');
                    wrapper.classList.add('scale-100', 'opacity-100');
                }, 50);
            }, 1000);
        }
    });

    document.getElementById('popup-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closePopup();
    });
</script>

<style>
    .prose-primary {
        color: #94a3b8;
    }

    .prose-primary h1,
    .prose-primary h2,
    .prose-primary h3,
    .prose-primary h4 {
        color: #fff;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: -0.02em;
    }

    .prose-primary a {
        color: #4ade80;
        text-decoration: underline;
        text-underline-offset: 4px;
        transition: 0.3s;
    }

    .prose-primary a:hover {
        color: #fff;
        text-shadow: 0 0 10px rgba(74, 222, 128, 0.5);
    }

    .prose-primary strong {
        color: #4ade80;
        font-weight: 900;
    }
</style>
@endif