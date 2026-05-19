@props(['items' => []])

@if(count($items) > 0)
<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center gap-2 text-sm text-neutral-400" itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach($items as $index => $item)
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="flex items-center gap-2">
            @if($loop->last)
            <span class="text-primary font-semibold" itemprop="name">{{ $item['name'] }}</span>
            @else
            <a href="{{ $item['url'] }}"
                class="hover:text-primary transition-colors"
                itemprop="item">
                <span itemprop="name">{{ $item['name'] }}</span>
            </a>
            <meta itemprop="position" content="{{ $index + 1 }}" />
            <span class="material-icons text-xs text-neutral-700">chevron_right</span>
            @endif
        </li>
        @endforeach
    </ol>
</nav>
@endif