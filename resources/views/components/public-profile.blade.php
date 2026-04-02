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
                         <span class="badge rounded-pill border text-primary bg-primary bg-opacity-10 px-3 py-1">
                            <x-heroicon-o-user-group class="icon-sm me-1"/>{{ $user->user_type->getLabel() }}
                        </span>

                        @if (auth()->user()->created_at->lt(auth()->user()->created_at->addWeeks(2)))
                            <span class="badge rounded-pill border text-dark bg-dark bg-opacity-10 px-3 py-1">
                                <x-heroicon-o-clock class="icon-sm me-1"/>Recent geregistreerd
                            </span>
                        @endif

                        @if (auth()->user()->hasVerifiedEmail())
                             <span class="badge rounded-pill border text-success bg-success bg-opacity-10 px-3 py-1">
                                <x-heroicon-o-shield-check class="icon-sm me-1"/> Geverifieerd
                            </span>
                        @endif
                    </div>
                </div>
                <div class="d-none d-md-flex gap-2">
                    @auth
                        @if (auth()->user()->is($user))
                            <a href="{{ route('profile:inbox') }}" class="btn btn-shadcn shadow-sm btn-outline-shadcn">
                               <x-heroicon-s-inbox class="icon me-1" style="width: 18px;"/> Mijn inbox

                                @if($user->unreadMessagesCount() > 0)
                                    <span class="ms-1 badge badge-gray">{{ $user->unreadMessagesCount() }}</span>
                                 @endif
                            </a>
                        @endif

                        @if (auth()->user()->isNot($user))
                            <a href="{{ route('inbox:create', ['participant' => $user->id]) }}" class="btn shadow-sm btn-shadcn btn-outline-shadcn">
                                <x-heroicon-o-envelope-open class="icon me-1" style="width: 18px;"/> bericht gebruiker
                            </a>
                        @endif

                        @if ($contactExist)
                            <form id="storeContact" action="{{ route('contacts:store') }}" method="POST" class="d-none">
                                @csrf
                                <input type="text" name="gebruikersnaam" value="{{ $user->name }}">
                            </form>

                            <a href="{{ route('contacts:store') }}" onclick="event.preventDefault(); document.getElementById('storeContact').submit();" class="btn btn-shadcn shadow-sm btn-outline-shadcn">
                                <x-heroicon-o-user-plus class="icon me-1" style="width: 18px;"/> contact toevoegen
                            </a>
                        @endif

                        @if (auth()->user()->is($user))
                            <a href="{{ route('definitions.create') }}" class="btn shadow-sm btn-shadcn btn-outline-shadcn">
                                <x-heroicon-o-pencil-square class="icon me-1" style="width: 18px;"/> Nieuw concept
                            </a>
                        @endif
                    @endauth

                    <a href="{{ route('definitions.create') }}" class="btn btn-shadcn shadow-sm btn-dark-shadcn">
                        <x-heroicon-o-plus class="icon me-1" style="width: 18px;"/> Suggestie indienen
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-5">
        <div class="col-lg-2">
            <div class="">
                <div class="sidenav-label">
                    <x-heroicon-s-magnifying-glass class="icon-sm me-1"/> Zoeken
                </div>

                <form method="GET" action="{{ request()->url() }}" class="position-relative">
                    <input type="text" name="zoekterm" value="{{ request()->input('zoekterm') }}" class="search-input-shadcn" placeholder="Woord zoeken...">
                </form>
            </div>

            <hr>

            <div class="mb-4">
                <nav class="nav flex-column">
                     <a href="{{ route('account:public', $user ?? auth()->user()) }}" class="sidenav-link {{ active('account:public') }} d-flex align-items-center">
                        <x-heroicon-o-globe-europe-africa class="icon color-green"/>
                        <span class="flex-grow-1">Publicaties</span>
                    </a>

                    @if ($user->is(auth()->user()))
                        <a href="{{ route('bookmarks:index') }}" class="sidenav-link {{ active('bookmarks:index') }} d-flex align-items-center">
                            <x-heroicon-o-bookmark class="icon color-green"/>
                            <span class="flex-grow-1">Bewaarde woorden</span>
                        </a>

                        <a href="" class="sidenav-link {{ active('bookmarks:index') }} d-flex align-items-center">
                            <x-heroicon-o-clipboard-document-list class="icon color-green"/>
                            <span class="flex-grow-1">Concepten</span>
                        </a>
                    @endif
                </nav>
            </div>

            @if (auth()->check() && $user->is(auth()->user()))
                <div class="mb-4">
                    <div class="sidenav-label">Mijn suggesties</div>

                    <nav class="nav flex-column">
                        <a href="{{ route('suggestions:index') }}" class="sidenav-link {{ active('suggestions:index') }} d-flex align-items-center">
                            <x-heroicon-o-document-text class="icon color-green"/>

                            <span class="flex-grow-1">Openstaande</span>
                            {{-- <span class="badge rounded-pill bg-count-badge ms-auto">{{ $totals->new }}</span> --}}
                        </a>


                        <a href="{{ route('suggestions:processing') }}" class="sidenav-link {{ active('suggestions:processing') }} d-flex align-items-center">
                            <x-heroicon-o-pencil-square class="icon color-green"/>

                            <span class="flex-grow-1">In behandeling</span>
                            {{-- <span class="badge rounded-pill bg-count-badge ms-auto">{{ $totals->approval + $totals->draft }}</span> --}}
                        </a>

                        <a href="{{ route('suggestions:archived') }}" class="sidenav-link {{ active('suggestions:archived') }} d-flex align-items-center">
                            <x-heroicon-o-archive-box class="icon color-green"/>

                            <span class="flex-grow-1">Gearchiveerd</span>
                            {{-- <span class="badge rounded-pill bg-count-badge ms-auto">{{ $totals->archived }}</span> --}}
                        </a>
                    </nav>
                </div>
            @endif

            @if($user->website || $user->bluesky || $user->twitter)
                <div class="px-1">
                    <div class="sidenav-label mb-2 d-flex align-items-center justify-content-between">
                        <span>Socials</span>
                    </div>

                    <nav class="nav flex-column gap-1">
                        @if ($user->website)
                            <a href="{{ $user->website }}" class="social-link-compact" title="Website">
                                <x-heroicon-s-globe-alt class="social-icon-sm" />
                                <span class="text-truncate">{{ $user->website }}</span>
                            </a>
                        @endif

                        @if ($user->bluesky)
                            <a href="https://bsky.app/profile/{{ $user->bluesky }}" class="social-link-compact" title="Bluesky">
                                <x-tabler-brand-bluesky class="social-icon-sm"/>
                                <span class="text-truncate">Bluesky</span>
                            </a>
                        @endif

                        @if ($user->twitter)
                            <a href="https://www.x.com/{{ ltrim($user->twitter, '@') }}" class="social-link-compact" title="Twitter">
                                <x-tabler-brand-x class="social-icon-sm"/>
                                <span class="text-truncate">Twitter</span>
                            </a>
                        @endif
                    </nav>
                </div>
            @endif
        </div>

        <div class="col-lg-9">
            <div class="row g-3 mb-4 text-center">
                <div class="col">
                    <div class="card-shadcn p-3 d-flex align-items-center justify-content-between border-primary border-opacity-25 text-start">
                        <div>
                            <div class="small text-secondary mb-1">Ingezonden suggesties</div>
                            <div class="fw-bold h5 mb-0 text-primary">{{ $suggestionCount }}</div>
                        </div>
                        <div class="p-2 bg-primary bg-opacity-10 rounded text-primary">
                            <x-heroicon-s-paper-airplane style="width: 20px;"/>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-shadcn p-3 d-flex align-items-center justify-content-between border-primary border-opacity-25 text-start">
                        <div>
                            <div class="small text-secondary mb-1">Aantal Concepten</div>
                            <div class="fw-bold h5 mb-0 text-primary">{{ $conceptCount }}</div>
                        </div>
                        <div class="p-2 bg-primary bg-opacity-10 rounded text-primary">
                            <x-heroicon-s-globe-europe-africa style="width: 20px;"/>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-shadcn p-3 d-flex align-items-center justify-content-between border-primary border-opacity-25 text-start">
                        <div>
                            <div class="small text-secondary mb-1">Aantal Publicaties</div>
                            <div class="fw-bold h5 mb-0 text-primary">{{ $publicationCount }}</div>
                        </div>
                        <div class="p-2 bg-primary bg-opacity-10 rounded text-primary">
                            <x-heroicon-s-globe-europe-africa style="width: 20px;"/>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-shadcn p-3 d-flex align-items-center justify-content-between border-primary border-opacity-25 text-start">
                        <div>
                            <div class="small text-secondary mb-1">Aantal Weergaves</div>
                            <div class="fw-bold h5 mb-0 text-primary">{{ $viewsCount }}</div>
                        </div>
                        <div class="p-2 bg-primary bg-opacity-10 rounded text-primary">
                            <x-heroicon-s-eye style="width: 20px;"/>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-shadcn p-3 d-flex align-items-center justify-content-between border-primary border-opacity-25 text-start">
                        <div>
                            <div class="small text-secondary mb-1">Aantal kudos</div>
                            <div class="fw-bold h5 mb-0 text-primary">{{ $kudosCount }}</div>
                        </div>
                        <div class="p-2 bg-primary bg-opacity-10 rounded text-primary">
                            <x-heroicon-s-hand-thumb-up style="width: 20px;"/>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            {{ $slot }}
        </div>
    </div>
</div>
@endsection
