@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item border-0 shadow-sm disabled" aria-disabled="true">
                    <span class="page-link">
                        <x-heroicon-o-chevron-double-left class="icon color-green"/> Nieuwer
                    </span>
                </li>
            @else
                <li class="page-item border-0 shadow-sm">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <x-heroicon-o-chevron-double-left class="icon color-green"/> Nieuwer
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item border-0 shadow-sm">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Ouder <x-heroicon-o-chevron-double-right class="icon color-green"/>
                    </a>
                </li>
            @else
                <li class="page-item border-0 shadow-sm disabled" aria-disabled="true">
                    <span class="page-link">
                        Ouder <x-heroicon-o-chevron-double-right class="icon color-green"/>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
