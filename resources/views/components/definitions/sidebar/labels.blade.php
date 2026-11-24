@props(['word'])

@php
    $labels = $word->labels->reject(fn ($label) => $label->private);
@endphp

@if ($labels->isNotEmpty())
    <section id="article-labels" class="border-bottom">
        <x-definitions.sidebar.section-title icon="heroicon-o-tag">Labels</x-definitions.sidebar.section-title>

        <div class="d-flex flex-wrap gap-1 pb-2" id="labels-list">
            @foreach ($labels as $label)
                <a href="{{ route('label:show', $label) }}"
                   class="badge rounded-pill text-dark border bg-white text-decoration-none">
                    {{ $label->name }}
                </a>
            @endforeach
        </div>
    </section>
@endif
