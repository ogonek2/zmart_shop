@if ($paginator->lastPage() > 1)
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>&laquo;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        {{-- First Page --}}
        <li class="{{ ($paginator->currentPage() == 1) ? 'active' : '' }}">
            <a href="{{ $paginator->url(1) }}">1</a>
        </li>

        {{-- Pages Before Current Page --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $startPage = max($currentPage - 2, 2);
            $endPage = min($currentPage + 1, $lastPage - 1);
        @endphp

        @for ($page = $startPage; $page <= $endPage; $page++)
            <li class="{{ ($page == $paginator->currentPage()) ? 'active' : '' }}">
                <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
            </li>
        @endfor

        {{-- Last Page --}}
        <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? 'active' : '' }}">
            <a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
        </li>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="disabled"><span>&raquo;</span></li>
        @endif
    </ul>
@endif
