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
        <meta name="robots" content="noindex, follow" />
    @endif
@endsection

@section ('content')
    <x-definitions.admin-management-nav :word=$word :articleResource=$articleResource/>

    <style>
        #article-content { font-size: 1rem; transition: font-size .2s ease; }
        #article-content h5 { font-size: 1em; }
        #article-content p, #article-content li, #article-content blockquote p { font-size: 1em; }
        /* Bootstrap 5 Default Equivalents */
        .font-size-sm { font-size: 0.875rem !important; } /* 14px */
        .font-size-md { font-size: 1rem     !important; } /* 16px (Base) */
        .font-size-lg { font-size: 1.25rem  !important; } /* 20px */
        .font-size-xl { font-size: 1.5rem   !important; } /* 24px */
        .markdown-text p:not(:last-child) { margin-bottom: .70rem; }
        .toolbar-btn.active { background-color: #0d6efd !important; color: #fff !important; border-color: #0d6efd !important; }
    </style>

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-10">
                @if ($word->disclaimer)
                    <div class="col-12">
                        <div class="alert alert-secondary alert-dismissible fade show shadow-sm small mb-4" role="alert">
                            <h5><x-heroicon-s-megaphone class="icon me-1"/><strong>Disclaimer</strong> </h5>
                            {{ $word->disclaimer->message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <div class="card shadow-sm border-0">
                    <!-- Card Header: breadcrumb + toolbar + meta badges -->
                    <div class="card-header bg-white border-bottom px-4 py-3">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

                            <!-- Breadcrumb -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="#" class="text-decoration-none">
                                            <x-heroicon-o-home class="icon me-1"/>{{ config('app.name', 'Laravel') }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('search.results') }}" class="text-decoration-none">
                                            <x-heroicon-o-magnifying-glass class="icon me-1"/> Zoeken
                                        </a>
                                    </li>

                                    <li class="breadcrumb-item active fw-semibold">{{ $word->word }}</li>
                                </ol>
                            </nav>

                            <!-- Right side: font toolbar + status badges -->
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <div class="d-flex align-items-center gap-3">
                                     @auth
                                        @if ($word->bookmarkers->contains(auth()->user()))
                                            <a href="{{ route('bookmark:remove', $word) }}" class="btn btn-outline-danger btn-sm">
                                                <x-heroicon-o-bookmark-slash class="me-1" style="width:1.1rem"/> Vergeet dit woord
                                            </a>
                                        @else
                                            <a href="{{ route('bookmark:create', $word) }}" class="btn btn-outline-success btn-sm">
                                                <x-heroicon-o-bookmark class="me-1" style="width:1.1rem"/> Bewaar
                                            </a>
                                        @endif

                                        <div class="vr"></div>
                                    @endauth
                                </div>

                                <!-- Font size toolbar -->
                                <div class="d-flex align-items-center gap-1">

                                    <span class="text-muted small me-1"><x-heroicon-o-language class="icon"/></span>

                                    <div class="btn-group btn-group-sm" role="group" aria-label="Font size">
                                        <button type="button" data-size="sm" class="btn btn-outline-secondary toolbar-btn" onclick="setFontSize('sm')" title="Small">A<sub>s</sub></button>
                                        <button type="button" data-size="md" class="btn btn-outline-secondary toolbar-btn" onclick="setFontSize('md')" title="Medium">A</button>
                                        <button type="button" data-size="lg" class="btn btn-outline-secondary toolbar-btn" onclick="setFontSize('lg')" title="Large">A<sup>+</sup></button>
                                        <button type="button" data-size="xl" class="btn btn-outline-secondary toolbar-btn" onclick="setFontSize('xl')" title="Extra large" style="font-size:1rem;font-weight:600;">A</button>
                                    </div>
                                </div>

                                <div class="vr"></div>

                                <!-- Status badges -->
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge text-bg-dark text-white">
                                        {{ toHumanReadableNumber($word->views) }} | Weergaves
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                <div class="card-body bg-white p-4" id="article-content">
                    <!-- ── HEADER: Word + actions (full width) ── -->
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-1">
                        <div>
                            <h1 class="display-5 color-green fw-bold mb-0">{{ $word->word }}</h1>
                            @if ($word->partOfSpeech)
                                <span class="fw-bold">{{ $word->partOfSpeech->name }}</span>
                                <span class="vertical-divider mx-1">|</span>
                            @endif

                            <span class="text-muted fst-italic">{{ $word->characteristics }}</span>
                        </div>
                    </div>

                    <hr>

                    <!-- Status + Regions (full width) -->
                    <div class="d-flex flex-wrap gap-3 mb-4 align-items-start">
                        <div>
                            <div class="text-muted small mb-1"><i class="bi bi-toggles me-1"></i>Status</div>
                                <div class="d-flex gap-1 flex-wrap">
                                    <span class="badge text-bg-success">{{ $word->status->getLabel() }}</span>
                                </div>
                            </div>

                            <div class="vr d-none d-sm-block"></div>

                            <div>
                                <div class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i>Regio's</div>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @forelse($word->regions as $region)
                                            <a href="{{ route('region:show', $region) }}" class="badge rounded-pill text-bg-primary">
                                                {{ $region->name }}
                                            </a>
                                        @empty
                                            <span class="badge rounded-pill text-bg-primary">
                                                Gans Vlaanderen
                                            </span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <hr />

                            @if ($word->isArchived())
                                <div class="alert alert-danger alert-dismissible fade show border-0">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                                    <h5 class="alert-heading fw-semibold">
                                        <x-heroicon-s-archive-box class="icon me-1"/> Gearchiveerd artikel
                                    </h5>

                                    <small>Dit artikel werd gearchiveerd om de volgende reden: {{ $word->archiving_reason }}</small>

                                    @if ($word->redirect_article_id)
                                        <hr>

                                        <a href="{{ route('word-information.show', $word->redirect_article_id) }}" class="btn btn-sm btn-outline-danger">
                                            <x-heroicon-s-eye class="icon me-1"/> Bekijk actueel verwijsartikel
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <!-- ══════════════════════════════════════════ 2-COLUMN LAYOUT  (col-8 main / col-4 sidebar) ══════════════════════════════════════════ -->
                            <div class="row g-4">
                                <!-- ── MAIN COLUMN (2/3) ── -->
                                <div class="col-lg-8">

                                    <!-- Description -->
                                    <section class="mb-4 pb-4 border-bottom">
                                        @if (flash()->message)
                                            <div class="alert {{ flash()->class }}" role="alert">
                                                {{ flash()->message }}
                                            </div>
                                        @endif

                                        <h5 class="fw-semibold mb-3">
                                            <span class="color-green fw-semibold me-1">//</span> Beschrijving
                                        </h5>

                                        <div class="d-flex flex-column flex-sm-row gap-3">
                                             @if ($word->image_url)
                                                <img
                                                    loading="lazy"
                                                    src="{{ $word->image_url }}"
                                                    alt="{{ $word->image_alt ?? $word->word }}"
                                                    class="rounded border-0 shadow-sm"
                                                    style="height: 200px; width: 200px; object-fit: cover;"
                                                />
                                            @endif

                                            <div class="markdown-text">
                                                {!! str($word->description)->markdown()->sanitizeHtml() !!}
                                            </div>
                                        </div>
                                    </section>

                                    <!-- Example -->
                                    <section class="mb-4 pb-4 border-bottom" id="examples">
                                        <h5 class="fw-semibold mb-3">
                                            <span class="color-green fw-semibold me-1">//</span> Voorbeeld(en)
                                        </h5>

                                        @if (! $word->migration_configuration['examples'])
                                            <ul class="nav nav-tabs" id="exampleTabs" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="tab-redactie" data-bs-toggle="tab" data-bs-target="#pane-redactie" type="button" role="tab" aria-controls="pane-redactie" aria-selected="true">
                                                        <x-heroicon-s-pencil-square class="icon me-1"/> Redactie
                                                    </button>
                                                </li>

                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="tab-community" data-bs-toggle="tab" data-bs-target="#pane-community" type="button" role="tab" aria-controls="pane-community" aria-selected="false">
                                                        <x-heroicon-s-users class="icon me-1"/> Community

                                                        @if ($exampleCount > 0)
                                                            <span class="text-muted fst-italic small">
                                                                ({{ $exampleCount }})
                                                            </span>
                                                        @endif
                                                    </button>
                                                </li>
                                            </ul>

                                            <div class="tab-content border border-top-0 rounded-bottom" id="exampleTabsContent">
                                                {{-- Redactie tab --}}
                                                <div class="tab-pane markdown-text bg-light-subtle fade show active p-3" id="pane-redactie" role="tabpanel" aria-labelledby="tab-redactie">
                                                    {!! str($word->example)->markdown()->sanitizeHtml() !!}
                                                </div>


                                                {{-- Community tab --}}
                                                <div class="tab-pane bg-light-subtle fade p-3" id="pane-community" role="tabpanel" aria-labelledby="tab-community">
                                                    <livewire:user-examples-list :articleId="$word->id" />

                                                    <hr class="my-3"/>

                                                    <livewire:submit-user-example cssClasses='mt-3' :articleId="$word->id" />
                                                </div>
                                            </div>
                                        @elseif($word->migration_configuration['examples'])
                                            <livewire:user-examples-list :articleId="$word->id" />

                                            <hr class="my-3"/>

                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body bg-lighte">
                                                    <livewire:submit-user-example :articleId="$word->id" />
                                                </div>
                                            </div>
                                        @endif
                                    </section>

                                    @if ($word->related->count() > 0)
                                        <section class="mb-4 pb-4 border-bottom">
                                            <h5 class="fw-semibold mb-3">
                                                <span class="color-green fw-semibold me-1">//</span> Gerelateerde artikelen
                                            </h5>

                                            <div class="d-flex flex-row flex-wrap gap-4">
                                                @foreach ($word->related as $related)
                                                    <a href="{{ route('word-information.show', $related) }}" class="d-flex gap-2 align-items-center text-decoration-none text-dark">
                                                        <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;">
                                                            <x-heroicon-s-book-open class="icon text-info"/>
                                                        </div>
                                                        <div>
                                                            <div class="small fw-medium lh-sm">{{ $related->word }}</div>
                                                            <div class="text-muted" style="font-size:.72rem;">{{ $word->partOfSpeech->name ?? '-' }}</div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </section>
                                    @endif

                                    @if($word->sources && $word->sources->count() > 0)
                                        <section class="mb-4 pb-4 border-bottom">
                                            <h5 class="fw-semibold mb-3">
                                                <span class="color-green fw-semibold me-1">//</span> Geraadpleegde bronnen
                                            </h5>

                                            <div class="d-flex flex-column gap-2" id="source-list">
                                                @foreach($word->sources as $source)
                                                    @if ($source->referenceWork)
                                                        <a href="{{ $source->referenceWork->external_url ?? "#" }}" class="text-decoration-none text-reset">
                                                            <div class="border bg-light bg-light-subtle shadow-sm rounded p-3 d-flex gap-3 align-items-start h-100 transition-hover">
                                                                <x-heroicon-s-book-open class="icon color-green flex-shrink-0 mt-1"/>

                                                                <div class="flex-grow-1">
                                                                    <div class="fw-medium small text-dark">{{ optional($source->referenceWork)->name }}</div>

                                                                    @if($source->notation)
                                                                        <div class="text-muted small">{{ $source->notation }}</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>

                                        </section>
                                    @endif

                                    <!-- Community Voting -->
                                    <livewire:voting-component :article="$word"/>
                                    <livewire:report-article-modal :article=$word />

                                    {{-- Wikipedia-stijl artikel footer --}}
                                    <div class="border-top mt-4 pt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <span class="small text-muted">
                                            <x-heroicon-o-clock class="icon me-1" style="width:13px;"/>
                                            Laatste wijziging: {{ optional($word->updated_at)->diffForHumans() }}
                                            @if($word->editor)
                                                door <a href="{{ route('account:public', $word->editor) }}" class="text-muted text-decoration-none fw-medium">{{ $word->editor->name }}</a>
                                            @endif
                                        </span>

                                        @if ($word->audits->count() > 0)
                                            <div class="d-flex gap-3">
                                                <a href="{{ route('article:revisions', $word) }}" class="small text-muted text-decoration-none d-flex align-items-center gap-1">
                                                    <x-heroicon-o-clock class="icon" style="width:13px;"/>
                                                    Bewerkingsgeschiedenis

                                                    @if(isset($revisionCount) && $revisionCount > 0)
                                                        <span class="badge bg-secondary-subtle text-secondary-emphasis fw-normal ms-1" style="font-size: .7rem;">
                                                            {{ $revisionCount }}
                                                        </span>
                                                    @endif
                                                </a>

                                                <span class="text-muted">·</span>

                                                <a href="{{ route('article:revisions', $word) }}?event=updated" class="small text-muted text-decoration-none d-flex align-items-center gap-1">
                                                    <x-heroicon-o-users class="icon" style="width:13px;"/>
                                                    Bijdragers
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div><!-- /col-lg-8 -->

                                <!-- ── SIDEBAR COLUMN (1/3) ── -->
                                <div class="col-lg-4">

                                <!-- Article Details -->
                                <div class="card border mb-3">
                                    <div class="card-header bg-light py-2 px-3">
                                        <span class="fw-semibold color-green">Gegevens artikel</span>
                                    </div>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between px-3">
                                            <span class="text-muted"><x-heroicon-o-user-circle class="icon color-green me-2"/> Ingezonden door</span>
                                            <span class="fw-medium text-end">
                                                 @if ($word->author()->exists())
                                                    <a href="{{ route('account:public', $word->author) }}" class="text-muted">{{ $word->author->name ?? $word->contributor_name }}</a>
                                                @else
                                                    <span class="fw-bold text-dark">{{ $word->contributor_name }}</span>
                                                @endif
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between px-3">
                                            <span class="text-muted"><x-heroicon-o-user-circle class="icon color-green me-2"/> Redactie door</span>
                                            <span class="fw-medium text-end">
                                                 @if ($word->editor()->exists())
                                                    <a href="{{ route('account:public', $word->editor) }}" class="text-muted">{{ $word->editor->name }}</a>
                                                @else
                                                    <span class="fw-bold text-dark">{{ config('app.name') }}</span>
                                                @endif
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between px-3">
                                            <span class="text-muted"><x-heroicon-o-user-circle class="icon color-green me-2"/> Publicatie door</span>
                                            <span class="fw-medium text-end">
                                                 @if ($word->publisher()->exists())
                                                    <a href="{{ route('account:public', $word->publisher) }}" class="text-muted">{{ $word->publisher->name }}</a>
                                                @else
                                                    <span class="fw-bold text-dark">{{ config('app.name') }}</span>
                                                @endif
                                            </span>
                                        </li>

                                        <li class="list-group-item d-flex justify-content-between px-3">
                                            <span class="text-muted"><x-heroicon-s-calendar-days class="icon color-green me-2"/> Publicatiedatum</span>
                                            <span class="fw-medium text-end">{{ optional($word->published_at)->translatedFormat('d F Y') ?? '-' }}</span>
                                        </li>
                                    </ul>
                                </div>

                                @if ($word->labels->count() > 0)
                                    <!-- Related Words -->
                                    <div class="card bg-white border mb-3">
                                        <div class="card-header bg-light py-2 px-3">
                                            <span class="fw-semibold color-green">Label(s)</span>
                                        </div>
                                        <div class="card-body px-3 py-2">
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($word->labels as $label)
                                                    @if (! $label->private)
                                                        <a href="{{ route('label:show', $label) }}" class="badge shadow-sm text-bg-light border text-dark text-decoration-none">
                                                            {{ $label->name }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (auth()->user() && $word->related->count() > 0 && $word->published())
                                    <hr>

                                    <section id="compare">
                                        <form action="{{ route('article:compare', ['word' => $word]) }}" method="GET">
                                            <label for="second_word" class="form-label fw-bold">
                                                Vergelijk dit woord met een gerelateerd woord
                                            </label>

                                            <div class="input-group">
                                                <select class="form-select" id="second_word" name="second_word" onchange="this.form.submit()">
                                                    <option value="" selected disabled>Selecteer een woord</option>

                                                    @foreach ($word->related as $related)
                                                        <option value="{{  $related->id }}">{{ $related->word }}</option>
                                                    @endforeach
                                                </select>

                                                <button type="submit" class="btn btn-dark">
                                                    Vergelijk
                                                </button>
                                            </div>
                                        </form>
                                    </section>
                                @endif

                                <hr>

                                <div class="btn-group border shadow-sm w-100" role="group" aria-label="Verbetering melden">
                                    @if (auth()->check() && auth()->user()->can('create', \App\Models\CorrectionProposal::class))
                                        <a href="{{ route('correction:create', $word) }}" class="btn btn-white w-100">
                                            <x-heroicon-o-pencil-square class="icon me-1"/>

                                            @if (auth()->user()->canPerform('artikel beschrijvingen bewerken'))
                                                <span>Artkel corriggeren</span>
                                            @else
                                                <span>Correctie voorstellen</span>
                                            @endif
                                        </a>
                                    @endif

                                    <button type="button" class="btn btn-danger" title="Een probleem melden" data-bs-toggle="modal" data-bs-target="#reportModal">
                                        <x-heroicon-s-exclamation-triangle class="icon"/>

                                        @cannot ('create', \App\Models\CorrectionProposal::class)
                                            <span class="ms-1">Een probleem melden</span>
                                        @endcannot
                                    </button>
                                </div>
                        </div><!-- /col-lg-4 -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const sizeMap = { sm: 'font-size-sm', md: 'font-size-md', lg: 'font-size-lg', xl: 'font-size-xl' };

    function setFontSize(size) {
        const content = document.getElementById('article-content');
        if (!content) return;

        // 1. Update Content Class
        content.classList.remove('font-size-sm','font-size-md','font-size-lg','font-size-xl');
        content.classList.add(sizeMap[size]);

        // 2. Persist to LocalStorage
        localStorage.setItem('preferred-font-size', size);

        // 3. Update Button Active States
        document.querySelectorAll('.toolbar-btn').forEach(btn => {
            // Remove active from all
            btn.classList.remove('active');
            // Add active if it matches the current size
            if (btn.getAttribute('data-size') === size) {
                btn.classList.add('active');
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        const savedSize = localStorage.getItem('preferred-font-size') || 'md';
        setFontSize(savedSize);
    });

    // If using Livewire 3 wire:navigate, use this instead of DOMContentLoaded:
    document.addEventListener('livewire:navigated', () => {
        const savedSize = localStorage.getItem('preferred-font-size') || 'md';
        setFontSize(savedSize);
    });
</script>
@endsection
