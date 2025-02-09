<small id="{{ $field }}" class="form-text text-muted">
    @if ((bool) $icon)
        <x-tabler-info-circle class="icon icon-sm me-1"/>
    @endif

    {{ $text }}
</small>
