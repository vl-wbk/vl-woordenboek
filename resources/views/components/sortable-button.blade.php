@props(['field', 'currentSort' => null])

@php
    $isActive = str_starts_with($currentSort, $field) || str_starts_with($currentSort, '-' . $field);
    $isDesc = str_starts_with($currentSort, '-' . $field);
    $nextSort = $isActive && !$isDesc ? '-' . $field : $field;
@endphp

<a href="{{ urldecode(request()->fullUrlWithQuery(['sort' => $nextSort])) }}" 
    {{ $attributes->merge(['class' => 'filter-link ' . ($isActive ? 'active' : '')]) }}>
    
    @if ($isActive)
        @if ($isDesc)
            <x-tabler-sort-ascending-letters class="icon-sm me-2"/>
        @else
            <x-tabler-sort-descending-letters class="icon-sm me-2"/>
        @endif
    @endif

    {{ $slot }}
</a>