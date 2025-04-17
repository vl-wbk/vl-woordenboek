<div class="row">
    <div class="col-12">
        <div class="float-start mb-2">
            <span class="fw-bold">{{ $results }}</span> artikelen gevonden
        </div>

        <div class="float-end mb-2">
            <ul class="list-inline mb-0">
                <li class="list-inline-item fw-bold text-muted">
                    Sorteer op:
                </li>

                <li class="list-inline-item">
                    <a href="{{ route('search.results', ['zoekterm' => request()->get('zoekterm')]) }}">Standaard</a>
                </li>

                <li class="list-inline-item text-muted">|</li>

                <li class="list-inline-item">
                    @if (request()->has('sort'))
                        <a href="{{ request()->fullUrlWithoutQuery(['sort']) }}">
                            <x-tabler-sort-ascending-letters class="icon color-green me-1"/> Publicatie
                        </a>
                    @else {{-- The order is descrinding --}}
                        <a href="{{ request()->fullUrlWithQuery(['sort' => '-published_at']) }}">
                            <x-tabler-sort-descending-letters class="icon color-green me-1"/> Publicatie
                        </a>
                    @endif
                </li>

                <li class="list-inline-item text-muted">|</li>

                <li class="list-inline-item active">
                    <a href="">Weergaves</a>
                </li>
            </ul>
        </div>
    </div>
</div>
