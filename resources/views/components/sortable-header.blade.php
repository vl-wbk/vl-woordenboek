
@props(['field', 'currentSort' => null])

@php
    $isActive = str_starts_with($currentSort, $field) || str_starts_with($currentSort, '-' . $field);
    $isDesc = str_starts_with($currentSort, '-' . $field);
    $nextSort = $isActive && !$isDesc ? '-' . $field : $field;
@endphp

<a href="{{ request()->fullUrlWithQuery(['sort' => $nextSort]) }}" class="text-muted">
    {{ $slot }}
    <span class="ms-2">
        @if($isActive)
            @if($isDesc)
                <x-tabler-sort-ascending class="icon color-green"/>
            @else
                <x-tabler-sort-descending class="icon color-green"/>
            @endif
        @endif
    </span>
</a>
