@props(['label', 'border' => true])

<div @class(['d-flex justify-content-between align-items-start gap-3 py-2', 'border-top' => $border])>
    <dt class="fw-normal text-muted mb-0">{{ $label }}</dt>
    <dd class="fw-medium text-end mb-0">{{ $slot }}</dd>
</div>
