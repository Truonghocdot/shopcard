<div class="bg-white rounded-xl border border-gray-200 shadow-md p-6">
    <h3 class="text-xl font-black mb-6 text-primary uppercase tracking-wide">Lịch sử giao dịch</h3>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <label class="block text-sm font-bold mb-2 text-gray-600 uppercase tracking-wide">Loại giao dịch</label>
            <select wire:model.live="filterType" class="w-full bg-white border border-gray-200 focus:border-primary rounded-lg px-4 py-2 text-gray-800">
                <option value="">Tất cả</option>
                <option value="0">Nạp tiền</option>
                <option value="1">Mua tài khoản</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-bold mb-2 text-gray-600 uppercase tracking-wide">Trạng thái</label>
            <select wire:model.live="filterStatus" class="w-full bg-white border border-gray-200 focus:border-primary rounded-lg px-4 py-2 text-gray-800">
                <option value="">Tất cả</option>
                <option value="0">Đang xử lý</option>
                <option value="1">Thành công</option>
                <option value="2">Thất bại</option>
            </select>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-gray-200">
                    <th class="text-left py-3 px-2 text-sm font-black text-primary uppercase tracking-wide">Mã GD</th>
                    <th class="text-left py-3 px-2 text-sm font-black text-primary uppercase tracking-wide">Loại</th>
                    <th class="text-right py-3 px-2 text-sm font-black text-primary uppercase tracking-wide">Số tiền</th>
                    <th class="text-center py-3 px-2 text-sm font-black text-primary uppercase tracking-wide">Trạng thái</th>
                    <th class="text-right py-3 px-2 text-sm font-black text-primary uppercase tracking-wide">Thời gian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-2 text-sm text-gray-800 font-bold">#{{ $transaction->id }}</td>
                    <td class="py-3 px-2 text-sm">
                        @if($transaction->service_type == 0)
                        <span class="text-green-600 font-semibold">Nạp tiền</span>
                        @else
                        <span class="text-red-500 font-semibold">Mua ACC</span>
                        @endif
                    </td>
                    <td class="py-3 px-2 text-sm text-right font-black text-gray-800">{{ number_format($transaction->amount) }} đ</td>
                    <td class="py-3 px-2 text-sm text-center">
                        @if($transaction->status == 0)
                        <span class="bg-yellow-50 border border-yellow-200 text-yellow-600 px-2 py-1 rounded text-xs font-bold">Đang xử lý</span>
                        @elseif($transaction->status == 1)
                        <span class="bg-green-50 border border-green-200 text-green-600 px-2 py-1 rounded text-xs font-bold">Thành công</span>
                        @else
                        <span class="bg-gray-50 border border-gray-200 text-red-500 px-2 py-1 rounded text-xs font-bold">Thất bại</span>
                        @endif
                    </td>
                    <td class="py-3 px-2 text-sm text-right text-gray-400">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-400">Chưa có giao dịch nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</div>