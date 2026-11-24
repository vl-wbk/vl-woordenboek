@props(['word', 'exampleCount' => 0, 'revisionCount' => 0])

@php
    $hasRelated = $word->related->isNotEmpty();
    $hasSources = (bool) $word->sources?->isNotEmpty();
@endphp

<article id="article-content">
    <ul class="nav nav-tabs" id="articleTabs" role="tablist">
        <x-definitions.tab-button id="beschrijving" icon="heroicon-o-document-text" active>
            Betekenis
        </x-definitions.tab-button>

        <x-definitions.tab-button id="voorbeelden" icon="heroicon-o-light-bulb" :count="$exampleCount">
            Voorbeelden
        </x-definitions.tab-button>

        @if ($hasRelated)
            <x-definitions.tab-button id="gerelateerd" icon="heroicon-o-link" :count="$word->related->count()">
                Gerelateerde woorden
            </x-definitions.tab-button>
        @endif

        @if ($hasSources)
            <x-definitions.tab-button id="bronnen" icon="heroicon-o-book-open">
                Bronnen
            </x-definitions.tab-button>
        @endif
    </ul>

    <div class="tab-content border border-top-0 bg-white rounded-bottom" id="articleTabsContent">
        <x-definitions.tabs.meaning :word="$word" :revision-count="$revisionCount"/>
        <x-definitions.tabs.examples :word="$word" :example-count="$exampleCount"/>

        @if ($hasRelated)
            <x-definitions.tabs.related :word="$word"/>
        @endif

        @if ($hasSources)
            <x-definitions.tabs.sources :word="$word"/>
        @endif
    </div>
</article>
