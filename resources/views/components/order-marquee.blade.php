{{-- Order Marquee Component --}}
@inject('viewDataService', 'App\Services\ViewDataService')

@php
$recentOrdersResult = $viewDataService->getRecentOrdersForMarquee(20);
$recentOrders = $recentOrdersResult->isSuccess() ? $recentOrdersResult->getData() : collect();
@endphp

<div class="border-y border-border py-1.5 md:py-3 overflow-hidden glass shadow-2xl">
    <div class="container mx-auto px-4 flex items-center gap-4">
        <!-- Icon & Label -->
        <div class="flex items-center gap-2 shrink-0">
            <div class="bg-primary/10 border border-primary/20 rounded-lg p-2">
                <span class="material-icons text-primary text-xl">shopping_cart</span>
            </div>
            <span class="text-text-primary font-black text-xs md:text-sm uppercase tracking-[0.2em] drop-shadow-[0_0_8px_rgba(34,197,94,0.3)]">Mua gần đây:</span>
        </div>

        <!-- Marquee Content -->
        <div class="flex-1 overflow-hidden min-w-0">
            <div class="marquee-content inline-flex flex-nowrap gap-6 items-center whitespace-nowrap">
                @if($recentOrders->count() > 0)
                @foreach($recentOrders as $order)
                <div class="flex items-center gap-3 shrink-0 glass border border-white/10 rounded-lg px-4 py-2 shadow-xl group hover:border-primary/50 transition-all">
                    <span class="material-icons text-primary text-sm">person</span>
                    <span class="text-neutral-100 font-bold text-sm">
                        {{ substr($order->user->name ?? 'User', 0, 3) }}***
                    </span>
                    <span class="text-neutral-700">→</span>
                    <span class="text-neutral-400 text-sm group-hover:text-white transition-colors">
                        {{ Str::limit($order->product->title ?? 'Sản phẩm', 30) }}
                    </span>
                    <span class="text-primary text-xs font-black drop-shadow-[0_0_5px_rgba(74,222,128,0.3)]">
                        +{{ number_format($order->final_amount, 0, ',', '.') }}đ
                    </span>
                </div>
                @endforeach

                <!-- Duplicate for seamless loop -->
                @foreach($recentOrders as $order)
                <div class="flex items-center gap-3 shrink-0 bg-bg-card border border-border rounded-lg px-4 py-2 hover:border-primary/50 transition-all shadow-lg">
                    <span class="material-icons text-primary/80 text-sm">person</span>
                    <span class="text-text-primary font-bold text-sm">
                        {{ substr($order->user->name ?? 'User', 0, 3) }}***
                    </span>
                    <span class="text-text-muted">→</span>
                    <span class="text-text-secondary text-sm group-hover:text-text-primary transition-colors">
                        {{ Str::limit($order->product->title ?? 'Sản phẩm', 30) }}
                    </span>
                    <span class="text-primary text-xs font-black drop-shadow-[0_0_5px_rgba(34,197,94,0.2)]">
                        +{{ number_format($order->final_amount, 0, ',', '.') }}đ
                    </span>
                </div>
                @endforeach
                @else
                <div class="text-gray-400 text-sm">Chưa có đơn hàng nào...</div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes marquee {
        0% {
            transform: translate3d(0, 0, 0);
        }

        100% {
            transform: translate3d(-50%, 0, 0);
        }
    }

    .marquee-content {
        width: max-content;
        animation: marquee 52s linear infinite;
        will-change: transform;
    }

    .marquee-content:hover {
        animation-play-state: paused;
    }
</style>