@php
    // Use the dedicated method to remove the 'sort' query parameter.
    // This is the cleanest and most reliable way to clear a parameter.
    $url = request()->fullUrlWithoutQuery('sort');
@endphp

@if (request()->has('sort'))
    <a href="{{ $url }}" class="btn btn-sm btn-outline-danger">
        <x-tabler-x class="icon me-1"/>
        {{ $slot->isEmpty() ? 'Clear Sort' : $slot }}
    </a>
@endif
