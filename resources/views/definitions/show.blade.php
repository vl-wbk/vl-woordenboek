@extends ('layouts.application-blank', ['title' => $word->word])

@section ('openGraph')
    <meta property="og:title" content="{{ $word->word }} - {{ config('app.name', 'Laravel') }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{{ request()->fullUrl() }}"/>
    <meta property="og:description" content="{{ $word->seo_description }}"/>
    <meta property="og:image" content="{{ asset('/img/app-logo.jpg') }}"/>
    <meta property="og:image:alt" content="Logo van het Vlaams woordenboek"/>
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}"/>
    <meta property="article:published_time" content="{{ optional($word->published_at)->toIso8601String() ?? \Illuminate\Support\Carbon::parse($word->created_at)->toIso8601String() }}"/>
    <meta property="article:modified_time" content="{{ optional($word->updated_at)->toIso8601String() ?? \Illuminate\Support\Carbon::now()->toIso8601String() }}"/>
    <meta property="og:article:author" content="{{ $word->editor->name ?? '' }}"/>
    <meta property="og:section" content="Linguïstiek"/>

    @if ($word->isArchived())
        <meta name="robots" content="noindex, follow"/>
    @endif
@endsection

@section ('content')
    <x-definitions.admin-management-nav :word=$word :articleResource=$articleResource/>

    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            <div class="col-11 col-xl-10">
                {{-- Dictionary header --}}
                <header id="word-header" class="border-bottom mt-4 pb-2 mb-3">

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small mb-3">
                            <li class="breadcrumb-item">
                                <a href="#" class="text-decoration-none text-muted">
                                    <x-heroicon-o-home class="icon me-1"/>
                                    {{ config('app.name', 'Laravel') }}
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('search.results') }}" class="text-decoration-none text-muted">
                                    <x-heroicon-o-magnifying-glass class="icon me-1"/>
                                    Zoeken
                                </a>
                            </li>

                            <li class="breadcrumb-item active fw-semibold"
                                aria-current="page">
                                {{ $word->word }}
                            </li>
                        </ol>
                    </nav>

                    <div class="d-flex flex-wrap align-items-end justify-content-between gap-2">

                        <div>
                            <div class="d-flex flex-wrap align-items-baseline gap-2">
                                <h1 class="display-5 color-green fw-bold lh-1 mb-0">
                                    {{ $word->word }}
                                </h1>

                                @if ($word->characteristics)
                                    <span class="fst-italic text-muted">
                                        {{ $word->characteristics }}
                                    </span>
                                @endif
                            </div>

                            @if ($word->partOfSpeech)
                                <div class="small text-muted mt-1">
                                    {{ $word->partOfSpeech->name }}
                                </div>
                            @endif
                        </div>

                        <div class="d-flex align-items-center flex-wrap gap-1">

                            <span class="badge rounded-pill text-dark shadow-sm bg-white border text-secondary fw-normal me-1">
                                <x-heroicon-o-eye class="icon me-1"/>
                                {{ toHumanReadableNumber($word->views) }}
                            </span>

                            <span class="badge rounded-pill text-bg-dark text-light shadow-sm border text-secondary fw-normal font-monospace">
                                <x-heroicon-o-hashtag class="icon me-1"/>{{ $word->id }}
                            </span>
                        </div>
                    </div>

                    <div id="word-metadata" class="d-flex flex-wrap align-items-center gap-2 mt-3">

                        <span class="badge rounded-pill text-bg-success">
                            <x-heroicon-o-language class="icon icon-sm me-1"/>{{ $word->status->getLabel() }}
                        </span>

                        <span class="vr"></span>

                        <span class="small text-muted">
                            <x-heroicon-o-map-pin class="icon me-1"/>
                            Regio(s):
                        </span>

                        <div class="d-flex flex-wrap gap-1">
                            @forelse($word->regions as $region)
                                <a href="{{ route('region:show', $region) }}" class="badge rounded-pill bg-white border text-dark text-decoration-none">
                                    {{ $region->name }}
                                </a>
                            @empty
                                <span class="badge rounded-pill text-bg-light border text-dark">
                                    Gans Vlaanderen
                                </span>
                            @endforelse
                        </div>
                    </div>
                </header>

                <div class="row g-3">
                    @if ($word->isArchived() || $word->disclaimer)
                        <div class="col-12">
                             @if ($word->isArchived())
                                <div id="archived-alert" class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-2" role="alert">
                                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                        <div class="d-flex align-items-start gap-2">
                                            <x-heroicon-s-archive-box class="flex-shrink-0 text-danger" style="width: 1.25rem; height: 1.25rem;" />

                                            <div class="lh-sm">
                                                <strong class="text-uppercase fw-bold">Gearchiveerd artikel:</strong>
                                                <span class="text-secondary-emphasis">Dit artikel werd gearchiveerd om de volgende reden:</span>
                                                <span>{{ $word->archiving_reason }}</span>
                                            </div>
                                        </div>

                                        @if ($word->redirect_article_id)
                                            <div class="flex-shrink-0 align-self-end align-self-md-center">
                                                <a id="redirect-btn"
                                                href="{{ route('word-information.show', $word->redirect_article_id) }}"
                                                class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1.5 shadow-sm">
                                                    <x-heroicon-s-eye class="flex-shrink-0" style="width: 1rem; height: 1rem;" />
                                                    <span class="text-nowrap">Bekijk actueel verwijsartikel</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @elseif ($word->disclaimer)
                                <div class="alert alert-secondary fade show  shadow-sm border-0 small mb-1 py-2" role="alert" id="disclaimer-alert">
                                    <div class="d-flex align-items-start gap-2">
                                        <x-heroicon-s-megaphone class="icon flex-shrink-0"/>
                                        <div>
                                            <strong>DISCLAIMER: </strong>
                                            <span class="ms-1">{{ $word->disclaimer->message }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Main entry --}}
                    <div class="col-lg-9">
                        <article id="article-content">
                            <ul class="nav nav-tabs" id="articleTabs" role="tablist">

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active d-flex align-items-center gap-1 py-2 fw-semibold"id="tab-beschrijving"
                                            data-bs-toggle="tab"
                                            data-bs-target="#pane-beschrijving"
                                            type="button"
                                            role="tab"
                                            aria-controls="pane-beschrijving"
                                            aria-selected="true">

                                        <x-heroicon-o-document-text class="icon"/>
                                        Betekenis
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link d-flex align-items-center gap-1 py-2"
                                            id="tab-voorbeelden"
                                            data-bs-toggle="tab"
                                            data-bs-target="#pane-voorbeelden"
                                            type="button"
                                            role="tab"
                                            aria-controls="pane-voorbeelden"
                                            aria-selected="false">

                                        <x-heroicon-o-light-bulb class="icon"/>
                                        Voorbeelden

                                        @if ($exampleCount > 0)
                                            <span class="badge text-bg-secondary fw-normal">
                                                {{ $exampleCount }}
                                            </span>
                                        @endif
                                    </button>
                                </li>

                                @if ($word->related->count() > 0)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center gap-1 py-2"
                                                id="tab-gerelateerd"
                                                data-bs-toggle="tab"
                                                data-bs-target="#pane-gerelateerd"
                                                type="button"
                                                role="tab"
                                                aria-controls="pane-gerelateerd"
                                                aria-selected="false">

                                            <x-heroicon-o-link class="icon"/>
                                            Gerelateerde woorden

                                            <span class="badge text-bg-secondary fw-normal">
                                                {{ $word->related->count() }}
                                            </span>
                                        </button>
                                    </li>
                                @endif

                                @if($word->sources && $word->sources->count() > 0)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center gap-1 py-2"
                                                id="tab-bronnen"
                                                data-bs-toggle="tab"
                                                data-bs-target="#pane-bronnen"
                                                type="button"
                                                role="tab"
                                                aria-controls="pane-bronnen"
                                                aria-selected="false">

                                            <x-heroicon-o-book-open class="icon"/>
                                            Bronnen
                                        </button>
                                    </li>
                                @endif

                            </ul>

                            <div class="tab-content border border-top-0 bg-white" style="border-bottom-left-radius: .375rem !important; border-bottom-right-radius: .375rem !important;"
                                 id="articleTabsContent">

                                {{-- Definition --}}
                                <div class="tab-pane fade show active"
                                     id="pane-beschrijving"
                                     role="tabpanel"
                                     aria-labelledby="tab-beschrijving">

                                    @if (flash()->message)
                                        <div class="alert {{ flash()->class }} border-0 rounded-0 small mb-0 py-2"
                                             role="alert"
                                             id="flash-alert">
                                            {{ flash()->message }}
                                        </div>
                                    @endif

                                    @if (session()->has('status'))
                                        <div class="alert alert-success border-0 rounded-0 small mb-0 py-2"
                                             role="alert">
                                            <strong>Succes:</strong>
                                            {{ session()->get('status') }}
                                        </div>
                                    @endif

                                    <div class="p-3"
                                         id="description-content">

                                        <div class="row g-3 align-items-start">
                                            @if ($word->image_url)
                                                <div class="col-sm-auto">
                                                    <figure class="mb-0">
                                                        <a href="{{ $word->image_url }}" target="_blank" rel="noopener">
                                                            <img
                                                                loading="lazy"
                                                                src="{{ $word->image_url }}"
                                                                alt="{{ $word->image_alt ?? $word->word }}"
                                                                class="rounded border object-fit-cover"
                                                                style="width:150px;height:150px;"
                                                                id="word-image"
                                                            />
                                                        </a>

                                                        @if ($word->image_alt)
                                                            <figcaption class="small text-muted mt-1">
                                                                {{ $word->image_alt }}
                                                            </figcaption>
                                                        @endif
                                                    </figure>
                                                </div>
                                            @endif

                                            <div class="{{ $word->image_url ? 'col' : 'col-12' }}"
                                                 id="description-text">



                                                <div class="markdown-text fs-6 lh-lg">
                                                    {!! str($word->description)->markdown()->sanitizeHtml() !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border-top mt-3 pt-2">
                                             <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

                                <div class="small text-muted">

                                    <x-heroicon-o-clock class="icon me-1"/>

                                    Laatste wijziging:
                                    {{ optional($word->updated_at)->diffForHumans() }}

                                    @if($word->editor)
                                        door
                                        <a href="{{ route('account:public', $word->editor) }}"
                                           class="text-muted text-decoration-none fw-semibold">
                                            {{ $word->editor->name }}
                                        </a>
                                    @endif
                                </div>

                                @if ($word->audits->count() > 0)
                                    <div class="d-flex gap-2"
                                         id="audit-links">

                                        <a href="{{ route('article:revisions', $word) }}"
                                           class="small text-muted text-decoration-none d-flex align-items-center gap-1"
                                           id="revision-history-link">

                                            <x-heroicon-o-clock class="icon"/>
                                            Bewerkingsgeschiedenis

                                            @if(isset($revisionCount) && $revisionCount > 0)
                                                <span class="badge bg-secondary-subtle text-secondary-emphasis fw-normal">
                                                    {{ $revisionCount }}
                                                </span>
                                            @endif
                                        </a>

                                        <span class="text-muted">·</span>

                                        <a href="{{ route('article:revisions', $word) }}?event=updated"
                                           class="small text-muted text-decoration-none d-flex align-items-center gap-1"
                                           id="contributors-link">

                                            <x-heroicon-o-users class="icon"/>
                                            Bijdragers
                                        </a>
                                    </div>
                                @endif

                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- Examples --}}
                                <div class="tab-pane fade"
                                     id="pane-voorbeelden"
                                     role="tabpanel"
                                     aria-labelledby="tab-voorbeelden">

                                    <div class="p-3">



                                        @if (! $word->migration_configuration['examples'])

                                            <ul class="nav nav-pills mb-3"
                                                id="examplePills"
                                                role="tablist">

                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active py-1 px-2 small"
                                                            id="pill-redactie-tab"
                                                            data-bs-toggle="pill"
                                                            data-bs-target="#pill-redactie"
                                                            type="button"
                                                            role="tab"
                                                            aria-selected="true">
                                                        <x-heroicon-s-pencil-square class="icon me-1"/>
                                                        Redactie
                                                    </button>
                                                </li>

                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link py-1 px-2 small"
                                                            id="pill-community-tab"
                                                            data-bs-toggle="pill"
                                                            data-bs-target="#pill-community"
                                                            type="button"
                                                            role="tab"
                                                            aria-selected="false">
                                                        <x-heroicon-s-users class="icon me-1"/>
                                                        Community

                                                        @if ($exampleCount > 0)
                                                            <span class="badge text-bg-secondary fw-normal ms-1">
                                                                {{ $exampleCount }}
                                                            </span>
                                                        @endif
                                                    </button>
                                                </li>
                                            </ul>

                                            <div class="tab-content"
                                                 id="examplePillContent">

                                                <div class="tab-pane fade show active"
                                                     id="pill-redactie"
                                                     role="tabpanel"
                                                     aria-labelledby="pill-redactie-tab">

                                                    <div class="py-1">
                                                        <div class="markdown-text">
                                                            {!! str($word->example)->markdown()->sanitizeHtml() !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade"
                                                     id="pill-community"
                                                     role="tabpanel"
                                                     aria-labelledby="pill-community-tab">

                                                    <livewire:user-examples-list :articleId="$word->id"/>

                                                    <hr class="my-3"/>

                                                    <livewire:submit-user-example
                                                        cssClasses="mt-2"
                                                        :articleId="$word->id"/>
                                                </div>

                                            </div>

                                        @else

                                            <livewire:user-examples-list :articleId="$word->id"/>

                                            <hr class="my-3"/>

                                            <div class="bg-light p-2">
                                                <livewire:submit-user-example :articleId="$word->id"/>
                                            </div>

                                        @endif
                                    </div>
                                </div>

                                {{-- Related --}}
                                @if ($word->related->count() > 0)
                                    <div class="tab-pane fade"
                                         id="pane-gerelateerd"
                                         role="tabpanel"
                                         aria-labelledby="tab-gerelateerd">

                                        <div class="p-3">



                                            <div class="row g-3"
                                                 id="related-articles-list">

                                                @foreach ($word->related as $related)
                                                    <div class="col-sm-4">
                                                        <a href="{{ route('word-information.show', $related) }}"
                                                           class="d-flex align-items-center gap-2 border-bottom py-2 text-decoration-none text-dark">

                                                            <x-heroicon-s-book-open class="icon color-green flex-shrink-0"/>

                                                            <div class="min-w-0">
                                                                <div class="fw-semibold color-green">
                                                                    {{ $related->word }}
                                                                </div>

                                                                <div class="small text-muted fst-italic">
                                                                    {{ $related->partOfSpeech->name ?? '-' }}
                                                                </div>
                                                            </div>

                                                            <x-heroicon-o-chevron-right class="icon ms-auto text-muted flex-shrink-0"/>
                                                        </a>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Sources --}}
                                @if($word->sources && $word->sources->count() > 0)
                                    <div class="tab-pane fade"
                                         id="pane-bronnen"
                                         role="tabpanel"
                                         aria-labelledby="tab-bronnen">

                                        <div class="p-3">
                                            <div class="d-flex flex-column"
                                                 id="source-list">

                                                @foreach($word->sources as $source)
                                                    @if ($source->referenceWork)

                                                        <a href="{{ $source->referenceWork->external_url ?? '#' }}"
                                                           class="text-decoration-none text-reset">

                                                            <div class="d-flex gap-2 align-items-start @if (! $loop->last) border-bottom @endif py-2">

                                                                <x-heroicon-s-book-open
                                                                    class="icon color-green flex-shrink-0"/>

                                                                <div class="flex-grow-1">
                                                                    <div class="fw-semibold small">
                                                                        {{ optional($source->referenceWork)->name }}
                                                                    </div>

                                                                    @if($source->notation)
                                                                        <div class="text-muted small">
                                                                            {{ $source->notation }}
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                @if ($source->referenceWork->external_url)
                                                                    <x-heroicon-o-arrow-top-right-on-square
                                                                        class="icon text-muted flex-shrink-0"/>
                                                                @endif

                                                            </div>
                                                        </a>

                                                    @endif
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>

                        </article>

                        @auth
                            <section id="voting-section" class="border-top mt-3 pt-3">
                                <livewire:voting-component :article="$word"/>
                            </section>

                            <livewire:report-article-modal :article=$word />
                        @endauth
                    </div>

                    {{-- Sidebar --}}
                    <aside class="col-lg-3">

                        {{-- Article details --}}
                        <section id="article-details"
                                 class="border-bottom">

                            <div class="d-flex align-items-center color-green gap-2 py-2">
                                <x-heroicon-o-information-circle class="icon me-A"/>
                                <h2 class="h6 fw-bold mb-0">
                                    Gegevens artikel
                                </h2>
                            </div>

                            <dl class="mb-0 small">

                                <div class="d-flex justify-content-between align-items-start gap-3 py-2"
                                     id="submitted-by-item">

                                    <dt class="fw-normal text-muted mb-0">
                                        Ingezonden door
                                    </dt>

                                    <dd class="fw-medium text-end mb-0">
                                        @if ($word->author()->exists())
                                            <a href="{{ route('account:public', $word->author) }}"
                                               class="text-decoration-none color-green">
                                                {{ $word->author->name ?? $word->contributor_name }}
                                            </a>
                                        @else
                                            {{ $word->contributor_name ?? 'Anonieme gebruiker' }}
                                        @endif
                                    </dd>
                                </div>

                                <div class="d-flex justify-content-between align-items-start gap-3 border-top py-2"
                                     id="edited-by-item">

                                    <dt class="fw-normal text-muted mb-0">
                                        Redactie door
                                    </dt>

                                    <dd class="fw-medium text-end mb-0">
                                        @if ($word->editor()->exists())
                                            <a href="{{ route('account:public', $word->editor) }}"
                                               class="text-decoration-none color-green">
                                                {{ $word->editor->name }}
                                            </a>
                                        @else
                                            {{ config('app.name') }}
                                        @endif
                                    </dd>
                                </div>

                                <div class="d-flex justify-content-between align-items-start gap-3 border-top py-2"
                                     id="published-by-item">

                                    <dt class="fw-normal text-muted mb-0">
                                        Publicatie door
                                    </dt>

                                    <dd class="fw-medium text-end mb-0">
                                        @if ($word->publisher()->exists())
                                            <a href="{{ route('account:public', $word->publisher) }}"
                                               class="text-decoration-none color-green">
                                                {{ $word->publisher->name }}
                                            </a>
                                        @else
                                            {{ config('app.name') }}
                                        @endif
                                    </dd>
                                </div>

                                <div class="d-flex justify-content-between align-items-start gap-3 border-top py-2"
                                     id="published-date-item">

                                    <dt class="fw-normal text-muted mb-0">
                                        Publicatiedatum
                                    </dt>

                                    <dd class="fw-medium text-end mb-0">
                                        {{ optional($word->published_at)->translatedFormat('d F Y') ?? '-' }}
                                    </dd>
                                </div>

                            </dl>
                        </section>

                        {{-- Labels --}}
                        @if ($word->labels->count() > 0)
                            <section id="article-labels" class="border-bottom">

                                <div class="d-flex align-items-center color-green gap-2 py-3">
                                    <x-heroicon-o-tag class="icon color-green"/>
                                    <h2 class="h6 fw-bold mb-0">Labels</h2>
                                </div>

                                <div class="pb-2"
                                     id="labels-list">

                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($word->labels as $label)
                                            @if (! $label->private)
                                                <a href="{{ route('label:show', $label) }}"
                                                   class="badge rounded-pill text-dark border bg-white text-decoration-none">
                                                    {{ $label->name }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>

                                </div>
                            </section>
                        @endif

                        {{-- Regional map --}}
                        @if ($word->region_chart)
                            <section id="dialect-map"
                                     class="border-bottom">

                                <div class="d-flex align-items-center gap-2 py-3">
                                    <x-heroicon-o-map class="icon color-green"/>
                                    <h2 class="h6 fw-semibold mb-0">
                                        Regionale verspreiding
                                    </h2>
                                </div>

                                <div class="pb-2">

                                    <a href="{{ asset('storage/' . $word->region_chart) }}"
                                       target="_blank"
                                       rel="noopener">

                                        <img
                                            src="{{ asset('storage/' . $word->region_chart) }}"
                                            alt="Regionale verspreiding van {{ $word->word }}"
                                            class="img-fluid rounded border"
                                            loading="lazy"
                                        />
                                    </a>

                                    @if ($word->region_chart_source)
                                        <p class="text-muted small mt-1 mb-0">
                                            Bron: {{ $word->region_chart_source }}
                                        </p>
                                    @endif

                                </div>
                            </section>
                        @endif

                        <div class="mt-3">
                            <x-thema-list-component :word="$word" />
                        </div>

                        {{-- Editorial actions --}}
                        <section class="@if (auth()->check()) border-top mt-3 pt-3 @endif">
                            <ul class="list-unstyled mb-0">
                                @can('create', \App\Models\CorrectionProposal::class)
                                    <li class="mb-1">
                                        <a href="{{ route('correction:create', $word) }}" class="text-secondary fw-semibold text-decoration-none small" id="correct-btn">
                                            <x-heroicon-s-pencil-square class="icon me-1"/>

                                            @if (auth()->user()->canPerform('artikel beschrijvingen bewerken'))
                                                Artikelbeschrijving corrigeren
                                            @else
                                                Correctie beschrijving voorstellen
                                            @endif
                                        </a>
                                    </li>
                                @endcan

                                @auth
                                    <li class="mb-2">
                                         @if ($word->bookmarkers->contains(auth()->user()))
                                            <a href="{{ route('bookmark:remove', $word) }}" class="text-danger fw-semibold text-decoration-none small" id="bookmark-btn-remove">
                                                <x-heroicon-o-bookmark-slash class="icon me-1"/> Vergeet dit woord
                                            </a>
                                        @else
                                            <a href="{{ route('bookmark:create', $word) }}" class="text-success fw-semibold text-decoration-none small" id="bookmark-btn-add">
                                                <x-heroicon-o-bookmark class="icon me-1"/> Bewaar dit woord
                                            </a>
                                        @endif
                                    </li>
                                @endauth

                                <li class="@auth border-top pt-2 mt-2 pb-2 @endauth">
                                    <a href="#" class="fw-semibold text-danger text-decoration-none small" data-bs-toggle="modal" data-bs-target="#reportModal" id="report-btn">
                                        <x-tabler-message-report class="icon me-1"/> Tip de redactie
                                    </a>
                                </li>
                            </ul>
                        </section>
                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection
