@props(['icon', 'tag' => 'h2'])

<div class="d-flex align-items-center color-green gap-2 py-2">
    <x-dynamic-component :component="$icon" class="icon"/>
    <{{ $tag }} class="h6 fw-bold mb-0">{{ $slot }}</{{ $tag }}>
</div>
