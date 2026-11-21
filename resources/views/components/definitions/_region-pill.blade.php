@php $active = $selectedIds->contains($region->id); @endphp

<a href="{{ $regionUrl($region->id) }}"
   class="btn btn-sm rounded-pill d-inline-flex align-items-center gap-1 {{ $active ? 'btn-primary' : 'btn-outline-secondary' }}"
   aria-pressed="{{ $active ? 'true' : 'false' }}"
>
    @if ($active)
        <x-heroicon-s-check-circle class="icon"/>
    @else
        <x-heroicon-o-map-pin class="icon"/>
    @endif

    {{ $region->name }}

    @isset($region->results_count)
        <span class="opacity-75">({{ $region->results_count }})</span>
    @endisset
</a>
