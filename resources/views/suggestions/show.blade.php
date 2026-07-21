@extends('layouts.application-blank', ['title' => $user->name, 'paddingContent' => 'pb-4 mb-5'])

@section('content')
<style>
    :root {
        --background: #ffffff;
        --foreground: #09090b;
        --muted: #f4f4f5;
        --muted-foreground: #71717a;
        --border: #e4e4e7;
        --radius: 0.6rem;
        --ring: #18181b;
    }

    body {
        /* background-color: var(--background); */
        /* color: var(--foreground); */
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
    }

    .card-shadcn {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .avatar {
        width: 80px; height: 80px;
        background-color: var(--muted);
        border: 1px solid var(--border);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: 600;
    }

    .btn-shadcn {
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: var(--radius);
        padding: 0.5rem 1rem;
        transition: all 0.2s;
    }

    .bg-count-badge {
        background-color: #2C2E31;
    }

    .btn-dark-shadcn { background-color: var(--ring); color: white; border: 1px solid var(--muted); }
    .btn-dark-shadcn:hover { background-color: #27272a; color: white; }

    .btn-outline-shadcn { background: white; border: 1px solid var(--border); color: var(--foreground); }
    .btn-outline-shadcn:hover { background-color: var(--muted); }

    /* Sidenav Styles */
    .sidenav-label {
        font-size: 0.70rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--muted-foreground);
        margin-bottom: 0.75rem;
        padding-left: 0.5rem;
    }

    .sidenav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: var(--foreground);
        text-decoration: none;
        border-radius: var(--radius);
        transition: background 0.2s;
    }

    .sidenav-link:hover { background-color: white !important; color: var(--foreground); }
    .sidenav-link.active { background-color: white; font-weight: 600; }
    .sidenav-link i, .sidenav-link svg { width: 16px; height: 16px; color: var(--muted-foreground); }

    .search-input-shadcn {
        font-size: 0.875rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0.4rem 0.75rem;
        background-color: white;
        width: 100%;
        transition: border-color 0.2s;
    }

    .search-input-shadcn:focus { outline: none; border-color: var(--ring); }

    /* Word Item Styles */
    .word-item {
        transition: background-color 0.2s ease;
        cursor: pointer;
        border-bottom: 1px solid var(--border);
    }
    .word-item:hover { background-color: #fafafa; }

    .nav-tabs-shadcn { border-bottom: 1px solid var(--border); margin-bottom: 2rem; }
    .nav-tabs-shadcn .nav-link { border: none; color: var(--muted-foreground); border-bottom: 2px solid transparent; padding: 0.5rem 1rem; font-size: 0.9rem; font-weight: 500; }
    .nav-tabs-shadcn .nav-link.active { color: var(--foreground); border-bottom-color: var(--foreground); background: none; }

    .badge-status { font-size: 0.65rem; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600; text-transform: uppercase; }
    .status-verified { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

    /* Custom Pagination */
    .pagination .page-link {
        border: 1px solid var(--border);
        color: var(--foreground);
        font-size: 0.875rem;
        border-radius: 4px;
        margin: 0 2px;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--ring);
        color: white;
        border-color: var(--ring);
    }

    .border-dashed {
    border-style: dashed !important;
    border-width: 2px !important;
    background-color: #fafafa !important; /* Iets grijzer dan de witte pagina */
}

.text-muted-foreground {
    color: var(--muted-foreground);
}

/* Subtiele animatie bij hover op de blank slate */
.card-shadcn.border-dashed:hover {
    border-color: var(--muted-foreground) !important;
    transition: border-color 0.3s ease;
}

.text-xs {
    font-size: 0.65rem;
    letter-spacing: 0.025em;
}

.hover-shadow:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.badge-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.10rem 0.4rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--foreground);
    background-color: var(--muted);
    border: 1px solid var(--border);
    border-radius: calc(var(--radius) - 4px);
    text-decoration: none;
    transition: all 0.2s ease;
}

.badge-chip:hover {
    background-color: #e4e4e7; /* Iets donkerder op hover */
    border-color: #d4d4d8;
    color: var(--foreground);
}

.icon-xs {
    width: 12px;
    height: 12px;
}

    .social-link-compact {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 8px; /* Zeer compacte padding */
        font-size: 0.875rem;
        color: var(--foreground);
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.1s ease-in-out;
    }

    .social-link-compact:hover {
        background-color: white !important; color: var(--foreground);
    }

    /* Fix voor icon-uitlijning */
    .social-icon-sm {
        width: 16px !important;
        height: 16px !important;
        flex-shrink: 0;
        opacity: 0.6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .social-link-compact:hover .social-icon-sm {
        opacity: 1;
        color: var(--foreground);
    }

    /* Subtiele animatie voor de tekst bij hover */
    .social-link-compact span {
        transition: transform 0.1s ease;
    }

    .social-link-compact:hover span {
        transform: translateX(2px);
    }

</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center pt-3">
        <div class="col-lg-11">
            <div class="d-flex align-items-center gap-4 mb-5">
                <img src="{{ $user->getFilamentAvatarUrl() }}" loading="lazy" class="avatar shadow-sm"/>

                <div class="flex-grow-1">
                    <h2 class="fw-bold mb-0">{{ $user->name }}</h2>
                    <p class="text-muted small mb-2">{{ $user->bio ?? 'Gebruiker van het Vlaams Woordenboek' }}</p>

                    <div class="d-flex gap-2 text-uppercase fw-bold" style="font-size: 0.80rem;">
                        @if ($user->user_type->in(enums: [\App\UserTypes::Developer, \App\UserTypes::Administrators]))
                            <span class="badge rounded-pill border text-secondary bg-secondary bg-opacity-10 px-2 py-1">
                                <x-tabler-users class="icon me-1"/> Kernlid
                            </span>
                        @elseif($user->user_type->in(enums: [\App\UserTypes::Editor, \App\UserTypes::EditorInChief]))
                            <span class="badge rounded-pill border text-secondary bg-secondary bg-opacity-10 px-2 py-1">
                                <x-tabler-users class="icon me-1"/> Redactie
                            </span>
                        @endif

                        @if ($user->hasVerifiedEmail())
                             <span class="badge rounded-pill border text-success bg-success bg-opacity-10 px-2 py-1">
                                <x-tabler-rosette-discount-check class="icon-sm me-1"/> Geverifieerd
                            </span>
                        @else
                            <span class="badge rounded-pill border-danger text-danger bg-danger bg-opacity-10 px-2 py-1">
                                <x-tabler-rosette-discount-check-off class="icon-sm me-1"/> Niet geverifieerd
                            </span>
                        @endif
                    </div>
                </div>
                <div class="d-none d-md-flex gap-2">
                    <a href="{{ route('concepts:create') }}" class="btn shadow-sm btn-shadcn btn-outline-shadcn">
                        <x-heroicon-o-pencil-square class="icon me-1" style="width: 18px;"/> Nieuw concept
                    </a>

                    <a href="{{ route('definitions.create') }}" class="btn btn-shadcn shadow-sm btn-dark-shadcn">
                        <x-heroicon-o-plus class="icon me-1" style="width: 18px;"/> Suggestie indienen
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-5">
        <div class="col-lg-2">


            <div class="mb-4">
                <nav class="nav flex-column">
                     <a href="{{ route('account:public', $user) }}" class="sidenav-link {{ active('account:public') }} d-flex align-items-center">
                        <x-heroicon-o-globe-europe-africa class="icon color-green"/>
                        <span class="flex-grow-1">Publicaties</span>
                    </a>

                    @if ($user->is(auth()->user()))
                        <a href="{{ route('bookmarks:index') }}" class="sidenav-link {{ active('bookmarks:index') }} d-flex align-items-center">
                            <x-heroicon-o-bookmark class="icon color-green"/>
                            <span class="flex-grow-1">Bewaarde woorden</span>
                        </a>

                        <a href="{{ route('concepts:index') }}" class="sidenav-link {{ active(['concepts:index', 'concepts:create']) }} d-flex align-items-center">
                            <x-heroicon-o-clipboard-document-list class="icon color-green"/>
                            <span class="flex-grow-1">Concepten</span>
                        </a>
                    @endif
                </nav>
            </div>

            <hr>


            <div class="mb-2">
                <div class="sidenav-label">Mijn suggesties</div>

                <nav class="nav flex-column">
                    <a href="{{ route('suggestions:index', ['status' => \App\Enums\ArticleStates::New->value]) }}" class="sidenav-link {{ active('suggestions:index') }} d-flex align-items-center">
                        <x-heroicon-o-document-text class="icon color-green"/>

                        <span class="flex-grow-1">Openstaande</span>
                        {{-- <span class="badge rounded-pill bg-count-badge ms-auto">{{ $totals->new }}</span> --}}
                    </a>
                    <a href="{{ route('suggestions:index', ['status' => \App\Enums\ArticleStates::Approval]) }}" class="sidenav-link {{ active('suggestions:processing') }}">
                        <x-heroicon-o-pencil-square class="icon color-green"/>
                        <span class="flex-grow-1">In behandeling</span>
                    </a>
                    <a href="{{ route('suggestions:index', ['status' => \App\Enums\ArticleStates::Archived->value]) }}" class="sidenav-link {{ active('suggestions:archived') }}">
                        <x-heroicon-o-archive-box class="icon color-green"/>
                        <span class="flex-grow-1">Gearchiveerd</span>
                    </a>
                </nav>
            </div>


           <hr>

           <div class="text-muted">
                <p class="mb-1">
                    Ingediend op <br>
                    <time datetime="2025-03-12" class="text-dark fw-semibold">{{ $article->created_at->translatedFormat('d F Y') }}</time>
                </p>

                @if ($article->created_at->lt($article->updated_at))
                    <small class="text-secondary">
                        Bijgewerkt <time datetime="P2D">2 dagen geleden</time>
                    </small>
                @endif
            </div>
        </div>

        <div class="col-lg-9">

            <div class="row g-4">
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-1">
                    <ol class="breadcrumb mb-0" style="font-size: 0.75rem;">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-muted">VL Woordenboek</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('suggestions:index') }}" class="text-muted">Mijn suggesties</a></li>
                        <li class="breadcrumb-item active" aria-current="page">suggestie #{{ $article->id }}</li>
                    </ol>
                </nav>
                <h1 class="fw-bold h3 mb-0">{{ $article->word }}</h1>
            </div>

            <span class="badge rounded-pill border text-primary bg-primary bg-opacity-10 px-3 py-1 text-uppercase fw-bold text-xs">
                <x-heroicon-s-tag class="icon-xs me-1"/> {{ $article->state->getLabel() }}
            </span>
        </div>

        <div class="card-shadcn p-3 mb-4">
            @if ($article->isArchived())
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger mb-0 fade show border-0">
                            <h6 class="alert-heading fw-semibold">
                                <x-heroicon-s-archive-box class="icon me-1"/> Gearchiveerde record
                            </h6>

                            <small>Dit artikel werd gearchiveerd om de volgende reden: {{ $article->archiving_reason }}</small>
                        </div>
                    </div>
                </div>

                <hr>
            @endif

            @if (flash()->message)
                <div class="row">
                    <div class="col-12">
                        <div class="alert {{ flash()->class }} mb-0" role="alert">
                            {{ flash()->message }}
                        </div>
                    </div>
                </div>

                <hr>
            @endif


            <div class="row g-3">
                <div class="col-6">
                    <div class="card bg-light card-body h-100">
                        <h6 class="fw-bold">Woordsoort</h5>
                        <p class="text-light-emphasis">{{ $article->partOfSpeech->name ?? '- niet opgegeven' }}</p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card bg-light card-body h-100">
                        <h6 class="fw-bold">Kenmerken</h5>
                        <p class="text-light-emphasis">
                            {{ $article->characteristics ?? '-niet opgegeven ' }}
                        </p>
                    </div>
                </div>


                <div class="col-12">
                    <div class="card bg-light card-body">
                        <h6 class="fw-bold">Regio(s)</h5>
                        <p class="text-light-emphasis">
                            @foreach ($article->regions as $region)
                                <span @if (!$loop->first) class="me-1" @endif>{{ $region->name }},</span>
                            @endforeach
                        </p>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card bg-light card-body">
                        <h6 class="fw-bold">Beschrijving</h5>

                        <div class="text-light-emphasis">
                            {!! str($article->description)->markdown()->sanitizeHtml() !!}
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card bg-light card-body">
                        <h6 class="fw-bold">Voorbeeldzinnen</h6>

                        @if ($article->userExamples()->exists())
                            <ul class="mb-0 list-unstyled space-y-4">
                                @foreach ($article->userExamples as $example)
                                    <li class="break-words @if (! $loop->last) pb-3 border-bottom mb-3 @endif">
                                        {{-- Main Sentence --}}
                                        <div class="text-gray-900">
                                            <span class="badge rounded-pill bg-dark text-white">{{ $loop->iteration }}</span>
                                            <span class="ms-2">{{ $example->example }}</span>
                                        </div>

                                        {{-- Source Media Object --}}
                                        <div class="d-flex align-items-center text-muted small ms-4">
                                            <cite class="italic ms-1">bron: {{ $example->source }}</cite>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            @if ($article->editor || $article->archiever || $article->publisher)
                <hr>

                <div class="row">
                    @if ($article->editor)
                        <div class="col-4">
                            <span class="text-uppercase text-muted fw-bold small mb-2 d-block" style="letter-spacing: 0.5px;">
                                Redactie door
                            </span>

                            <div class="card bg-white border rounded-4 p-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $article->editor->getFilamentAvatarUrl() }}" class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 33px; height: 33px; flex-shrink: 0;"/>

                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $article->editor->name ?? config('app.name', 'Laravel') }}</h6>
                                        <div class="text-muted small">{{ $article->editor->user_type->getLabel() ?? 'Verwijderde gebruiker' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($article->publisher)
                        <div class="col-4">
                            <span class="text-uppercase text-muted fw-bold small mb-2 d-block" style="letter-spacing: 0.5px;">
                                Eindredactie door
                            </span>

                            <div class="card bg-white border rounded-4 p-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $article->publisher->getFilamentAvatarUrl() }}" class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 33px; height: 33px; flex-shrink: 0;"/>

                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $article->publisher->name ?? config('app.name', 'Laravel') }}</h6>
                                        <div class="text-muted small">{{ $article->publisher->user_type->getLabel() ?? 'Verwijderde gebruiker' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($article->archiever)
                        <div class="col-4">
                            <span class="text-uppercase text-muted fw-bold small mb-2 d-block" style="letter-spacing: 0.5px;">
                                Gearchiveerd door
                            </span>

                            <div class="card bg-white border rounded-4 p-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $article->archiever->getFilamentAvatarUrl() }}" class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 33px; height: 33px; flex-shrink: 0;"/>

                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $article->archiever->name ?? config('app.name', 'Laravel') }}</h6>
                                        <div class="text-muted small">{{ $article->archiever->user_type->getLabel() ?? 'Verwijderde gebruiker' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
