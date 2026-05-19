<div class="glass rounded-2xl border border-white/10 overflow-hidden shadow-2xl">
    <div class="p-6 border-b border-white/5 bg-white/5">
        <h2 class="text-white text-lg font-black uppercase tracking-tighter flex items-center gap-3">
            <span class="w-1 h-6 bg-primary rounded-full"></span>
            CẬP NHẬT THÔNG TIN
        </h2>
    </div>

    @if (session()->has('success'))
    <div class="px-6 pt-6 text-[10px] md:text-xs">
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center gap-3 font-black uppercase tracking-widest">
            <span class="material-icons text-sm">check_circle</span>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Affiliate Link Section -->
    <div class="px-6 pb-6 pt-6">
        <div class="bg-white/5 rounded-2xl p-6 border border-white/5">
            <h3 class="text-primary font-black mb-3 flex items-center gap-2 uppercase tracking-widest text-xs">
                <span class="material-icons text-base">share</span>
                LINK GIỚI THIỆU CỦA BẠN
            </h3>
            <p class="text-neutral-600 text-[10px] mb-6 font-bold uppercase tracking-widest">Chia sẻ link này để nhận 5% hoa hồng từ mỗi đơn hàng của người bạn giới thiệu</p>

            <div class="flex gap-2">
                <div class="flex-1 min-w-0 bg-neutral-950/50 border border-white/5 rounded-xl px-4 py-3 font-mono text-[10px] text-neutral-300 break-all select-all">
                    {{ $this->affiliateUrl }}
                </div>
                <button
                    type="button"
                    onclick="navigator.clipboard.writeText('{{ $this->affiliateUrl }}'); alert('Đã copy link giới thiệu!');"
                    class="shrink-0 btn-esport px-6 py-3 rounded-xl transition-all active:scale-95 flex items-center gap-2 font-black uppercase text-[10px] whitespace-nowrap shadow-primary/20">
                    <span class="material-icons text-sm">content_copy</span>
                    COPY
                </button>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="bg-neutral-950/40 border border-white/5 rounded-xl p-4">
                    <p class="text-neutral-600 text-[9px] uppercase font-black tracking-widest mb-1">NGƯỜI ĐÃ GIỚI THIỆU</p>
                    <p class="text-white font-black text-2xl tracking-tighter">{{ auth()->user()->referrals()->count() }}</p>
                </div>
                <div class="bg-neutral-950/40 border border-white/5 rounded-xl p-4">
                    <p class="text-neutral-600 text-[9px] uppercase font-black tracking-widest mb-1">TỔNG HOA HỒNG</p>
                    <p class="text-primary font-black text-2xl tracking-tighter drop-shadow-[0_0_8px_rgba(74,222,128,0.3)]">
                        {{ number_format(auth()->user()->commissionsEarned()->where('status', 'paid')->sum('commission_amount')) }}đ
                    </p>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="updateProfile" class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
                <label class="text-neutral-600 text-[10px] font-black uppercase tracking-widest pl-1">Họ và tên</label>
                <div class="relative group">
                    <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-[20px] transition-colors group-focus-within:text-primary">badge</span>
                    <input wire:model="name" type="text" readonly class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-neutral-950/50 border border-white/5 text-neutral-600 font-bold cursor-not-allowed uppercase tracking-wide text-sm">
                </div>
                @error('name') <span class="text-pink-500 text-[10px] mt-1 block font-black uppercase tracking-widest">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-3">
                <label class="text-neutral-600 text-[10px] font-black uppercase tracking-widest pl-1">Số điện thoại</label>
                <div class="relative group">
                    <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-[20px] transition-colors group-focus-within:text-primary">call</span>
                    <input wire:model="phone" type="text" class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-white/5 border border-white/5 focus:border-primary focus:ring-1 focus:ring-primary/20 outline-hidden transition-all font-black text-neutral-200 placeholder:text-neutral-800 text-sm">
                </div>
                @error('phone') <span class="text-pink-500 text-[10px] mt-1 block font-black uppercase tracking-widest">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-3">
                <label class="text-neutral-600 text-[10px] font-black uppercase tracking-widest pl-1">Email</label>
                <div class="relative group">
                    <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-neutral-700 text-[20px] transition-colors group-focus-within:text-primary">mail</span>
                    <input wire:model="email" type="email" class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-white/5 border border-white/5 focus:border-primary focus:ring-1 focus:ring-primary/20 outline-hidden transition-all font-black text-neutral-200 placeholder:text-neutral-800 text-sm">
                </div>
                @error('email') <span class="text-pink-500 text-[10px] mt-1 block font-black uppercase tracking-widest">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="border-t border-white/5 pt-8">
            <h3 class="text-white font-black mb-6 flex items-center gap-3 uppercase tracking-tighter">
                <span class="material-icons text-primary drop-shadow-[0_0_8px_rgba(74,222,128,0.4)]">lock</span>
                ĐỔI MẬT KHẨU
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3" x-data="{ show: false }">
                    <label class="text-neutral-600 text-[10px] font-black uppercase tracking-widest pl-1">Mật khẩu hiện tại</label>
                    <div class="relative w-full group">
                        <input wire:model="current_password" :type="show ? 'text' : 'password'"
                            class="w-full pl-4 pr-12 py-3.5 rounded-2xl bg-white/5 border border-white/5 focus:border-primary focus:ring-1 focus:ring-primary/20 outline-hidden transition-all text-neutral-200 font-black text-sm">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-4 flex items-center text-neutral-700 hover:text-primary transition-colors z-10">
                            <span class="material-icons text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                    @error('current_password') <span class="text-pink-500 text-[10px] mt-1 block font-black uppercase tracking-widest">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-3" x-data="{ show: false }">
                    <label class="text-neutral-600 text-[10px] font-black uppercase tracking-widest pl-1">Mật khẩu mới</label>
                    <div class="relative w-full group">
                        <input wire:model="new_password" :type="show ? 'text' : 'password'"
                            class="w-full pl-4 pr-12 py-3.5 rounded-2xl bg-white/5 border border-white/5 focus:border-primary focus:ring-1 focus:ring-primary/20 outline-hidden transition-all text-neutral-200 font-black text-sm">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-4 flex items-center text-neutral-700 hover:text-primary transition-colors z-10">
                            <span class="material-icons text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                    @error('new_password') <span class="text-pink-500 text-[10px] mt-1 block font-black uppercase tracking-widest">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-3" x-data="{ show: false }">
                    <label class="text-neutral-600 text-[10px] font-black uppercase tracking-widest pl-1">Xác nhận mật khẩu mới</label>
                    <div class="relative w-full group">
                        <input wire:model="new_password_confirmation" :type="show ? 'text' : 'password'"
                            class="w-full pl-4 pr-12 py-3.5 rounded-2xl bg-white/5 border border-white/5 focus:border-primary focus:ring-1 focus:ring-primary/20 outline-hidden transition-all text-neutral-200 font-black text-sm">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-4 flex items-center text-neutral-700 hover:text-primary transition-colors z-10">
                            <span class="material-icons text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password2 Section -->
        <div class="border-t border-white/5 pt-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-white font-black flex items-center gap-3 uppercase tracking-tighter">
                    <span class="material-icons text-primary drop-shadow-[0_0_8px_rgba(74,222,128,0.4)]">enhanced_encryption</span>
                    ĐỔI MẬT KHẨU CẤP 2
                </h3>
                <button type="button" wire:click="togglePassword2Form"
                    class="text-primary hover:text-white text-[10px] font-black uppercase tracking-widest flex items-center gap-2 transition-all">
                    <span class="material-icons text-sm">{{ $showPassword2Form ? 'expand_less' : 'expand_more' }}</span>
                    {{ $showPassword2Form ? 'ẨN' : 'MỞ' }}
                </button>
            </div>

            @if($showPassword2Form)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white/5 p-6 rounded-2xl border border-white/5">


                <div class="space-y-3" x-data="{ show: false }">
                    <label class="text-neutral-600 text-[10px] font-black uppercase tracking-widest pl-1">MẬT KHẨU CẤP 2 MỚI (6 SỐ)</label>
                    <div class="relative w-full group">
                        <input wire:model="new_password2" :type="show ? 'text' : 'password'"
                            class="w-full pl-4 pr-12 py-3.5 rounded-2xl bg-neutral-950/50 border border-white/5 focus:border-primary focus:ring-1 focus:ring-primary/20 outline-hidden transition-all text-neutral-200 font-black text-sm"
                            placeholder="******" maxlength="6" inputmode="numeric">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-4 flex items-center text-neutral-700 hover:text-primary transition-colors z-10">
                            <span class="material-icons text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                    @error('new_password2') <span class="text-pink-500 text-[10px] mt-1 block font-black uppercase tracking-widest">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-3" x-data="{ show: false }">
                    <label class="text-neutral-600 text-[10px] font-black uppercase tracking-widest pl-1">XÁC NHẬN MẬT KHẨU CẤP 2 MỚI</label>
                    <div class="relative w-full group">
                        <input wire:model="new_password2_confirmation" :type="show ? 'text' : 'password'"
                            class="w-full pl-4 pr-12 py-3.5 rounded-2xl bg-neutral-950/50 border border-white/5 focus:border-primary focus:ring-1 focus:ring-primary/20 outline-hidden transition-all text-neutral-200 font-black text-sm"
                            placeholder="******" maxlength="6" inputmode="numeric">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-4 flex items-center text-neutral-700 hover:text-primary transition-colors z-10">
                            <span class="material-icons text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                </div>

                <div class="md:col-span-2 flex justify-end pt-4">
                    <button type="button" wire:click="changePassword2"
                        class="btn-esport font-black py-4 px-10 rounded-2xl flex items-center gap-3 uppercase tracking-widest text-xs border-none shadow-primary/20 active:scale-95 transition-all">
                        <span class="material-icons text-[20px]">lock_reset</span>
                        <span wire:loading.remove wire:target="changePassword2">ĐỔI MẬT KHẨU CẤP 2</span>
                        <span wire:loading wire:target="changePassword2" class="flex items-center gap-2">
                            <span class="material-icons animate-spin text-sm">refresh</span>
                            ĐANG XỬ LÝ...
                        </span>
                    </button>
                </div>
            </div>
            @endif
        </div>

        <div class="flex justify-end pt-8">
            <button type="submit" class="btn-esport font-black py-5 px-12 rounded-2xl flex items-center gap-3 uppercase tracking-widest text-sm border-none shadow-primary/30 active:scale-95 transition-all">
                <span class="material-icons text-[22px]">save</span>
                <span wire:loading.remove>LƯU THAY ĐỔI</span>
                <span wire:loading class="flex items-center gap-2">
                    <span class="material-icons animate-spin text-sm">refresh</span>
                    ĐANG LƯU...
                </span>
            </button>
        </div>
    </form>
</div>