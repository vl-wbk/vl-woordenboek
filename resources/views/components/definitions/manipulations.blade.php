<hr>

<div class="card bg-white border-0 shadow-sm">
    <div class="list-group list-group-flush">
        @can ('update', $word)
            <a href="{{ route('definitions.update', $word) }}" class="list-group-item list-group-item-action">
                <x-heroicon-o-pencil-square class="icon me-1"/> Gegevens wijzigen
            </a>
        @endcan

        @can('delete', $word)
            <a href="" class="text-danger list-group-item list-group-item-action">
                <x-heroicon-o-trash class="icon me-1"/> Artikel verwijderen
            </a>
        @endcan

        @if(! auth()->user()->canAny(['delete', 'update'], $word))
            <a href="" class="text-danger list-group-item list-group-item-action">
                Probleem melden
            </a>
        @endif
    </div>
</div>
