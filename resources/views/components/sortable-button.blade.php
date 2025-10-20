@props(['field', 'currentSort' => null])

@php
    $isActive = str_starts_with($currentSort, $field) || str_starts_with($currentSort, '-' . $field);
    $isDesc = str_starts_with($currentSort, '-' . $field);
    $nextSort = $isActive && !$isDesc ? '-' . $field : $field;
@endphp

<a href="{{ urldecode(request()->fullUrlWithQuery(['sort' => $nextSort])) }}" class="btn btn-light">
    @if ($isActive)
        @if ($isDesc)
            <x-tabler-sort-ascending-letters class="icon color-green me-1"/>
        @else
            <x-tabler-sort-descending-letters class="icon color-green me-1"/>
        @endif
    @endif

    {{ $slot }}
</a>
