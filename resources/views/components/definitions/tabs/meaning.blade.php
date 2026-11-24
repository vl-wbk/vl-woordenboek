@props(['word', 'revisionCount' => 0])

@php
    $author = $word->author?->exists ? $word->author : null;
    $authorName = filled($author?->name)
        ? $author->name
        : ($word->contributor_name ?: 'Anonieme gebruiker');
@endphp

<div class="tab-pane fade show active" id="pane-beschrijving" role="tabpanel" aria-labelledby="tab-beschrijving">

    @if (flash()->message)
        <div id="flash-alert" class="alert {{ flash()->class }} border-0 rounded-0 small mb-0 py-2" role="alert">
            {{ flash()->message }}
        </div>
    @endif

    @if (session()->has('status'))
        <div class="alert alert-success border-0 rounded-0 small mb-0 py-2" role="alert">
            <strong>Succes:</strong> {{ session('status') }}
        </div>
    @endif

    <div class="p-3" id="description-content">
        <div class="row g-3 align-items-start">

            @if ($word->image_url)
                <div class="col-sm-auto">
                    <figure class="mb-0">
                        <a href="{{ $word->image_url }}" target="_blank" rel="noopener">
                            <img id="word-image"
                                 class="word-image rounded border object-fit-cover"
                                 loading="lazy"
                                 src="{{ $word->image_url }}"
                                 alt="{{ $word->image_alt ?? $word->word }}">
                        </a>

                        @if ($word->image_alt)
                            <figcaption class="small text-muted mt-1">{{ $word->image_alt }}</figcaption>
                        @endif
                    </figure>
                </div>
            @endif

            <div id="description-text" @class([$word->image_url ? 'col' : 'col-12'])>

                @auth
                    @unless ($word->syllables)
                        <section class="suggest-syllables">
                            <livewire:suggest-syllables :article="$word"/>
                            <hr style="border-style: dashed;">
                        </section>
                    @endunless
                @endauth

                <div class="markdown-text mb-2">
                    {!! str($word->description)->markdown()->sanitizeHtml() !!}
                </div>



                <div class="border-top pt-3 mt-3" >
                    <div class="card bg-light border-dashed shadow-none" style="border-style: dashed !important;">
                        <div class="card-body py-2 px-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                            <p class="mb-0 text-body-secondary small">
                                <strong class="text-body fw-semibold">Nog geen etymologie bekend.</strong>
                                Weet jij waar dit woord vandaan komt?
                            </p>
                            <a href="{{-- route('...') --}}" class="btn btn-sm btn-outline-primary text-nowrap">
                                Etymologie toevoegen
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="border-top mt-3 pt-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

                <ul class="list-inline mb-0 text-muted small">
                    <li class="list-inline-item">
                        <strong>Inzender:</strong>

                        @if ($author && filled($author->getRouteKey()))
                            <a href="{{ route('account:public', $author) }}" class="fw-bold color-green">
                                {{ $authorName }}
                            </a>
                        @else
                            <span class="fw-bold">{{ $authorName }}</span>
                        @endif
                    </li>

                    @if ($word->isPublished())
                        <li class="list-inline-item">|</li>
                        <li class="list-inline-item">
                            <strong>Publicatiedatum:</strong>
                            {{ $word->published_at->translatedFormat('d F Y') }}
                        </li>
                    @endif
                </ul>

                @if ($word->audits->isNotEmpty())
                    <div class="d-flex gap-2" id="audit-links">
                        <a id="revision-history-link"
                           href="{{ route('article:revisions', $word) }}"
                           class="small text-muted text-decoration-none d-flex align-items-center gap-1">
                            <x-heroicon-o-clock class="icon"/>
                            Bewerkingsgeschiedenis

                            @if ($revisionCount > 0)
                                <span class="badge bg-secondary-subtle text-secondary-emphasis fw-normal">
                                    {{ $revisionCount }}
                                </span>
                            @endif
                        </a>

                        <span class="text-muted">·</span>

                        <a id="contributors-link"
                           href="{{ route('article:revisions', $word) }}?event=updated"
                           class="small text-muted text-decoration-none d-flex align-items-center gap-1">
                            <x-heroicon-o-users class="icon"/>
                            Bijdragers
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
