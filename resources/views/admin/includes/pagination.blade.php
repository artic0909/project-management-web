@if ($paginator->hasPages())
    <div class="tf-pagination" style="display:flex; gap:6px; align-items:center;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button class="pg-btn" disabled style="opacity:0.5; cursor:not-allowed;"><i class="bi bi-chevron-left"></i></button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pg-btn"><i class="bi bi-chevron-left"></i></a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="pg-ellipsis" style="padding:0 8px; color:var(--t4);">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="pg-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pg-btn"><i class="bi bi-chevron-right"></i></a>
        @else
            <button class="pg-btn" disabled style="opacity:0.5; cursor:not-allowed;"><i class="bi bi-chevron-right"></i></button>
        @endif
    </div>
@endif
