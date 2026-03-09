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
                                <span class="opacity-50 mx-1">|</span>
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
                                            <span class="badge rounded-pill text-bg-primary">
                                                {{ $region->name }}
                                            </span>
                                        @empty
                                            <span class="badge rounded-pill text-bg-primary">
                                                Gans Vlaanderen
                                            </span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <hr />

                            <!-- ══════════════════════════════════════════ 2-COLUMN LAYOUT  (col-8 main / col-4 sidebar) ══════════════════════════════════════════ -->
                            <div class="row g-4">
                                @if ($word->disclaimer)
                                    <div class="col-12">
                                        <div class="alert alert-warning shadow-sm small mb-0" role="alert">
                                            <h5><x-heroicon-s-megaphone class="icon me-1"/><strong>Disclaimer</strong> </h5>
                                            {{ $word->disclaimer->message }}
                                        </div>
                                    </div>
                                @endif

                                <!-- ── MAIN COLUMN (2/3) ── -->
                                <div class="col-lg-8">

                                    <!-- Description -->
                                    <section class="mb-4 pb-4 border-bottom">
                                        <h5 class="fw-semibold mb-3">
                                            <span class="color-green fw-semibold me-1">//</span> Beschrijving
                                        </h5>

                                        <div class="d-flex flex-column flex-sm-row gap-3">
                                             @if ($word->image_url)
                                                <img
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
                                    <section class="mb-4 pb-4 border-bottom">
                                        <h5 class="fw-semibold mb-3">
                                            <span class="color-green fw-semibold me-1">//</span> Voorbeeld(en)
                                        </h5>

                                        <div class="card card-body border-0 bg-light text-secondary">
                                            {!! str($word->example)->markdown()->sanitizeHtml() !!}
                                        </div>
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
                                                        <div class="border bg-light bg-light-subtle shadow-sm rounded p-3 d-flex gap-3 align-items-start">
                                                            <x-heroicon-s-book-open class="icon color-green flex-shrink-0 mt-1"/>

                                                            <div class="flex-grow-1">
                                                                <div class="fw-medium small">{{ optional($source->referenceWork)->name }}</div>
                                                                
                                                                @if($source->notation)
                                                                    <div class="text-muted small">{{ $source->notation }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                            
                                        </section>
                                    @endif 

                                    <!-- Community Voting -->
                                    <livewire:voting-component :article="$word"/>
                                    <livewire:report-article-modal :article=$word />
                                </div><!-- /col-lg-8 -->

                                <!-- ── SIDEBAR COLUMN (1/3) ── -->
                                <div class="col-lg-4">
                                    

                                <!-- Article Details -->
                                <div class="card border mb-3">
                                    <div class="card-header bg-light py-2 px-3">
                                        <span class="fw-semibold color-green">Artikel gegevens</span>
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
                                        <li class="list-group-item d-flex justify-content-between px-3">
                                            <span class="text-muted"><x-heroicon-s-calendar-days class="icon color-green me-2"/> Laatste wijziging</span>
                                            <span class="fw-medium text-end">{{ optional($word->updated_at)->translatedFormat('d F Y') ?? '-' }}</span>
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
                                                    <a href="{{ route('label:show', $label) }}" class="badge shadow-sm text-bg-light border text-dark text-decoration-none">
                                                        {{ $label->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                         </div><!-- /col-lg-4 -->
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
