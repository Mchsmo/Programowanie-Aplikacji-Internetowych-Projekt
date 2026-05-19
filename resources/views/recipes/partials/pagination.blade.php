@if (isset($paginator) && $paginator->hasPages())
    <div style="margin-top: 3em; text-align: center; display: flex; justify-content: center; gap: 0.5em; align-items: center;">
        
        {{-- Przycisk Wstecz --}}
        @if ($paginator->onFirstPage())
            <span class="button alt small disabled" style="opacity: 0.5; pointer-events: none; height: 2.5em; line-height: 2.5em; padding: 0 1.5em;">&laquo; Poprzednia</span>
        @else
            <a href="{{ $paginator->appends(request()->query())->previousPageUrl() }}" class="button alt small" style="height: 2.5em; line-height: 2.5em; padding: 0 1.5em;">&laquo; Poprzednia</a>
        @endif

        {{-- Numery stron --}}
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="button small" style="background: #ed786a; color: #fff; border-color: #ed786a; height: 2.5em; line-height: 2.5em; width: 2.5em; padding: 0; text-align: center;">{{ $page }}</span>
            @else
                <a href="{{ $paginator->appends(request()->query())->url($page) }}" class="button alt small" style="height: 2.5em; line-height: 2.5em; width: 2.5em; padding: 0; text-align: center;">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Przycisk Dalej --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->appends(request()->query())->nextPageUrl() }}" class="button alt small" style="height: 2.5em; line-height: 2.5em; padding: 0 1.5em;">Następna &raquo;</a>
        @else
            <span class="button alt small disabled" style="opacity: 0.5; pointer-events: none; height: 2.5em; line-height: 2.5em; padding: 0 1.5em;">Następna &raquo;</span>
        @endif

    </div>
    
    <div style="text-align: center; margin-top: 0.8em; font-size: 0.8em; color: #999;">
        Wyświetlono wpisy od {{ $paginator->firstItem() }} do {{ $paginator->lastItem() }} z {{ $paginator->total() }} pozycji.
    </div>
@endif