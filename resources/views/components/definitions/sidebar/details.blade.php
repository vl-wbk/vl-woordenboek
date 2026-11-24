@props(['word'])

@php
    $syllables = $word->syllablesHtml();
@endphp

<section id="article-details" class="border-bottom">
    <x-definitions.sidebar.section-title icon="heroicon-o-information-circle">
        Gegevens artikel
    </x-definitions.sidebar.section-title>

    <dl class="mb-0 small">
        <x-definitions.sidebar.detail-row label="Woordsoort" :border="false">
            <span class="text-muted">{{ $word->partOfSpeech?->name ?? '- geen opgegeven' }}</span>
        </x-definitions.sidebar.detail-row>

        <x-definitions.sidebar.detail-row label="Lettergrepen">
            {{ $syllables ?: '-' }}
        </x-definitions.sidebar.detail-row>

        <x-definitions.sidebar.detail-row label="Taalregister">
            <span class="badge rounded-pill text-bg-success">
                <x-heroicon-o-language class="icon icon-sm me-1"/>{{ $word->status->getLabel() }}
            </span>
        </x-definitions.sidebar.detail-row>
    </dl>
</section>
