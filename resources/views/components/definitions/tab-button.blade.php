@props(['id', 'icon', 'active' => false, 'count' => null])

<li class="nav-item" role="presentation">
    <button type="button"
            role="tab"
            id="tab-{{ $id }}"
            @class(['nav-link d-flex align-items-center gap-1 py-2', 'active fw-semibold' => $active])
            data-bs-toggle="tab"
            data-bs-target="#pane-{{ $id }}"
            aria-controls="pane-{{ $id }}"
            aria-selected="{{ $active ? 'true' : 'false' }}">
        <x-dynamic-component :component="$icon" class="icon"/>
        {{ $slot }}

        @if ($count)
            <span class="badge text-bg-secondary fw-normal">{{ $count }}</span>
        @endif
    </button>
</li>
