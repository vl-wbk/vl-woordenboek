@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination border-0 pagination-sm mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item border-0 shadow-sm disabled" aria-disabled="true">
                    <span class="page-link">
                        <x-heroicon-o-chevron-double-left class="icon icon-sm"/> recentere reacties
                    </span>
                </li>
            @else
                <li class="page-item border-0 shadow-sm">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <x-heroicon-o-chevron-double-left class="icon icon-sm"/> recentere reacties
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item border-0 shadow-sm">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        oudere reacties <x-heroicon-o-chevron-double-right class="icon icon-sm"/>
                    </a>
                </li>
            @else
                <li class="page-item border-0 shadow-sm disabled" aria-disabled="true">
                    <span class="page-link">
                        oudere reacties <x-heroicon-o-chevron-double-right class="icon icon-sm"/>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
