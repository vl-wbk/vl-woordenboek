<div class="row">
    <div class="col-12">
        <div class="float-start mb-2">
            @if (request('zoekterm') === null)
                <span class="fw-bold">0</span> resultaten
            @else
                <span class="fw-bold">{{ $results }}</span> resultaten
            @endif
        </div>

        <div class="float-end mb-2">
            <ul class="list-inline mb-0">
                <li class="list-inline-item fw-bold text-muted">
                    Sorteer op:
                </li>

                <li class="list-inline-item">
                    @if (request('sort') === '-alfabetisch')
                        <a href="{{ request()->fullUrlWithoutQuery(['sort']) }}">
                            <x-tabler-sort-ascending-letters class="icon color-green me-1"/> Alfabetische volgorde
                        </a>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['sort' => '-alfabetisch']) }}">
                            <x-tabler-sort-descending-letters class="icon color-green me-1"/> Alfabetische volgorde
                        </a>
                    @endif
                </li>

                <li class="list-inline-item text-muted">|</li>

                <li class="list-inline-item">
                    @if (request('sort') === '-publicatie')
                        <a href="{{ request()->fullUrlWithoutQuery(['sort']) }}">
                            <x-tabler-sort-ascending-letters class="icon color-green me-1"/> Publicatiedatum
                        </a>
                    @else {{-- The order is descrinding --}}
                        <a href="{{ request()->fullUrlWithQuery(['sort' => '-publicatie']) }}">
                            <x-tabler-sort-descending-letters class="icon color-green me-1"/> Publicatiedatum
                        </a>
                    @endif
                </li>

                <li class="list-inline-item text-muted">|</li>

                <li class="list-inline-item active">
                    @if (request('sort') === '-weergaves')
                        <a href="{{ request()->fullUrlWithoutQuery(['sort']) }}">
                            <x-tabler-sort-ascending-letters class="icon color-green me-1"/> Weergaves
                        </a>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['sort' => '-weergaves']) }}">
                            <x-tabler-sort-descending-letters class="icon color-green me-1"/> Weergaves
                        </a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</div>
