@props(['word'])

@if ($word->region_chart)
    @php $chartUrl = asset('storage/' . $word->region_chart); @endphp

    <section id="dialect-map" class="border-bottom">
        <x-definitions.sidebar.section-title icon="heroicon-o-map">
            Regionale verspreiding
        </x-definitions.sidebar.section-title>

        <div class="pb-2">
            <a href="{{ $chartUrl }}" target="_blank" rel="noopener">
                <img src="{{ $chartUrl }}"
                     alt="Regionale verspreiding van {{ $word->word }}"
                     class="img-fluid rounded border"
                     loading="lazy">
            </a>

            @if ($word->region_chart_source)
                <p class="text-muted small mt-1 mb-0">Bron: {{ $word->region_chart_source }}</p>
            @endif
        </div>
    </section>
@endif
