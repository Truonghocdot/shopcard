<div>
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 transform transition-all scale-100 overflow-hidden border border-gray-200">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full border border-red-200 mb-4">
                    <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-black text-primary mb-2 uppercase tracking-wide">
                    Thiết lập Mật khẩu cấp 2
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                    Để bảo vệ tài khoản, vui lòng thiết lập mật khẩu giao dịch (6 số, cấp quyền truy cập thông tin tài khoản đã mua). Đây KHÔNG phải mật khẩu đăng nhập.
                </p>

                <form wire:submit.prevent="savePin">
                    <div class="mb-4 text-left">
                        <label for="pin" class="block text-sm font-bold text-gray-600 mb-1 uppercase tracking-wide">Mật khẩu cấp 2 (6 số)</label>
                        <input type="password" wire:model="pin" id="pin"
                            class="w-full px-3 py-2 bg-white border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary rounded-md outline-none text-center tracking-widest text-lg text-gray-800"
                            placeholder="******" maxlength="6" inputmode="numeric">
                        @error('pin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6 text-left">
                        <label for="pin_confirmation" class="block text-sm font-bold text-gray-600 mb-1 uppercase tracking-wide">Nhập lại mật khẩu</label>
                        <input type="password" wire:model="pin_confirmation" id="pin_confirmation"
                            class="w-full px-3 py-2 bg-white border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary rounded-md outline-none text-center tracking-widest text-lg text-gray-800"
                            placeholder="******" maxlength="6" inputmode="numeric">
                    </div>

                    <button type="submit"
                        class="w-full btn-esport inline-flex justify-center rounded-md px-4 py-2 text-base font-black sm:text-sm transition-all duration-200 uppercase tracking-wide">
                        Xác nhận và Lưu
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>