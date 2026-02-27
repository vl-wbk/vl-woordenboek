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
    <style>
        .markdown-text p:not(:last-child) { margin-bottom: .70rem; }
    </style>

    <x-definitions.admin-management-nav :word=$word :articleResource=$articleResource/>


    <div class="word-header py-5 border-bottom bg-light">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="d-flex justify-content-between mb-3 align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><x-heroicon-o-home class="icon me-1"/>Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('search.results') }}"><x-heroicon-o-magnifying-glass class="icon me-1"/>Zoeken</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $word->word }}</li>
                            </ol>
                        </nav>
                        <a href="{{ url()->previous() }}" class="back-link">
                            <x-heroicon-o-arrow-left style="width:1rem"/>
                            Terug naar vorige pagina
                        </a>
                    </div>

                    <h1 class="display-3 fw-bold mb-1">{{ $word->word }}</h1>

                    <div class="d-flex flex-column gap-2">
                        @if($word->regions->isNotEmpty())
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @foreach($word->regions as $region)
                                    <a href="{{ route('region:show', $region) }}"
                                       class="text-decoration-none d-flex align-items-center text-uppercase"
                                       style="font-size: 0.75rem; font-weight: 700; color: var(--lexi-region-text); letter-spacing: 0.025em;">
                                        <x-heroicon-s-map-pin style="width:0.9rem; margin-right: 0.2rem;"/>
                                        {{ $region->name }}
                                    </a>
                                    @if(!$loop->last) <span class="text-muted opacity-25">|</span> @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="d-flex align-items-center flex-wrap my-2 gap-2 text-muted">
                            @if ($word->partOfSpeech)
                                <span class="fw-bold text-dark">{{ $word->partOfSpeech->name }}</span>
                                <span class="opacity-50">•</span>
                            @endif
                            <span class="font-monospace">{{ $word->characteristics }}</span>
                        </div>

                        @if ($word->labels->isNotEmpty())
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($word->labels as $label)
                                    <a href="{{ route('label:show', $label) }}" class="shadow-sm word-label text-decoration-none">
                                        <x-heroicon-o-tag class="icon me-1" style="width: 1rem;"/>
                                        {{ $label->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                        <div class="header-actions mt-4 d-flex gap-3">
                            @auth
                                @if ($word->bookmarkers->contains(auth()->user()))
                                    <a href="{{ route('bookmark:remove', $word) }}" class="action-link-btn text-danger text-decoration-none opacity-75 d-flex align-items-center">
                                        <x-heroicon-o-bookmark-slash class="me-1" style="width:1.1rem"/> Vergeet dit woord
                                    </a>
                                @else
                                    <a href="{{ route('bookmark:create', $word) }}" class="action-link-btn text-decoration-none d-flex align-items-center">
                                        <x-heroicon-o-bookmark class="me-1" style="width:1.1rem"/> Bewaar
                                    </a>
                                @endif

                                <button class="action-link-btn text-danger opacity-75 bg-transparent border-0 p-0 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#reportModal">
                                    <x-heroicon-o-megaphone class="me-1" style="width:1.1rem"/> Verbetering melden
                                </button>
                            @endauth

                            <div class="font-controls d-flex align-items-center gap-2 ms-auto">
                                <span class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">Tekstgrootte:</span>

                                <div class="btn-group btn-group-sm shadow-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="changeFontSize(-1)" title="Kleiner">
                                        <x-heroicon-o-minus style="width:1rem"/>
                                    </button>

                                    <button type="button" class="btn btn-outline-secondary" onclick="resetFontSize()" title="Standaard">
                                        <x-heroicon-o-arrow-path style="width:1rem"/>
                                    </button>

                                    <button type="button" class="btn btn-outline-secondary" onclick="changeFontSize(1)" title="Groter">
                                        <x-heroicon-o-plus style="width:1rem"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    @if ($word->disclaimer)
        <div class="alert alert-info border-0 mb-0" role="alert">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <p>{{ $word->disclaimer->message }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 pe-lg-5" id="readable-content">
                <section class="mb-5">
                    <h5 class="fw-bold mb-3 text-success">Definitie</h5>

                    <div class="border-bottom mb-3">
                        <dl class="row">
                            <dt class="col-sm-2">Status</dt>
                            <dd class="col-sm-10">{{ $word->status->getLabel() }}</dd>
                            <dt class="col-sm-2">Varianten</dt>
                            <dd class="col-sm-10 mb-0">{{ $word->keywords ?? '-' }}</dd>
                        </dl>
                    </div>

                    <div class="d-flex">
                        @if ($word->image_url)
                            <div class="flex-shrink-0 d-sm-none d-md-block me-3">
                                <a href="{{ $word->image_url }}">
                                    <img
                                        src="{{ $word->image_url }}"
                                        alt="{{ $word->image_alt ?? $word->word }}"
                                        class="rounded border-0 shadow-sm"
                                        style="height: 200px; width: 200px; object-fit: cover;"
                                    />
                                </a>
                            </div>
                        @endif

                        <div class="flex-grow-1">
                            <div class="text-muted">
                                <div class="markdown-text lh-base text-dark">
                                    {!! str($word->description)->markdown()->sanitizeHtml() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold mb-3 color-green">Voorbeelden</h5>
                    <div class="markdown-text">
                        {!! str($word->example)->markdown()->sanitizeHtml() !!}
                    </div>
                </section>

                @if ($word->related()->exists())
                    <section class="mb-5">
                        <h5 class="fw-bold color-green mb-3">Gerelateerde Woorden</h5>
                        <div class="d-flex flex-wrap">
                            @foreach($word->related as $related)
                                <a href="{{ route('word-information.show', $related) }}" class="related-chip shadow-sm">
                                    <x-heroicon-o-document-text class="icon color-green me-1"/> {{ $related->word }}
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($word->sources && $word->sources->count() > 0)
                    <section class="mb-5">
                        <h5 class="fw-bold mb-3 d-flex align-items-center color-green">Bronnen & Referenties</h5>
                        <div class="sources-list">
                            @foreach($word->sources as $source)
                                <div class="source-item shadow-sm">
                                    <div class="source-icon">
                                        <x-heroicon-s-book-open style="width: 1.2rem;"/>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="source-link fw-semibold">{{ optional($source->referenceWork)->name }}</span>
                                        @if($source->notation)
                                            <p class="mb-0 small text-muted mt-1">{{ $source->notation }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="border-top pt-4">
                    <div class="contributor-box bg-white shadow-sm">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                            <x-heroicon-o-user style="width: 1.5rem;" class="text-muted" />
                        </div>
                        <div>
                            <p class="mb-1 small text-muted">
                                Toegevoegd door
                                @if ($word->author()->exists())
                                    <a href="{{ route('account:public', $word->author) }}" class="fw-bold text-dark">{{ $word->author->name ?? $word->contributor_name }}</a>
                                @else
                                    <span class="fw-bold text-dark">{{ $word->contributor_name }}</span>
                                @endif
                            </p>
                            <p class="mb-0 extra-small text-muted" style="font-size: 0.75rem;">
                                Gepubliceerd op {{ optional($word->published_at)->format('d M Y') ?? $word->created_at->format('d M Y') }}
                                <span class="vr mx-2"></span>
                                Laatst bijgewerkt op {{ $word->updated_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="col-lg-3">
                {{-- Community Stats --}}
                <livewire:voting-component :article="$word"/>
            </aside>
        </div>
    </div>

    <livewire:report-article-modal :article=$word />

        <script>
            function changeFontSize(direction) {
                const container = document.getElementById('readable-content');
                // Get current computed font size or default to 1rem (16px)
                const currentSize = parseFloat(window.getComputedStyle(container).getPropertyValue('font-size'));

                // Define limits (e.g., between 12px and 32px)
                const newSize = currentSize + (direction * 2);

                if (newSize >= 14 && newSize <= 28) {
                    container.style.fontSize = newSize + 'px';

                    // Optional: Save preference to localStorage
                    localStorage.setItem('preferred-font-size', newSize + 'px');
                }
            }

            function resetFontSize() {
                const container = document.getElementById('readable-content');
                container.style.fontSize = ''; // Reverts to CSS default
                localStorage.removeItem('preferred-font-size');
            }

            // Apply saved preference on load
            window.addEventListener('DOMContentLoaded', () => {
                const savedSize = localStorage.getItem('preferred-font-size');
                if (savedSize) {
                    document.getElementById('readable-content').style.fontSize = savedSize;
                }
            });
        </script>
@endsection
