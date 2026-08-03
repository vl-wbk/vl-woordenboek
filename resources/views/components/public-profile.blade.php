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
        border-color: var(--muted-foreground) !important;
    border-style: dashed !important;
    border-width: 2px !important;
}

.text-muted-foreground {
    color: var(--muted-foreground);
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

                    <div class="d-flex gap-2 text-uppercase fw-bold">
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
                    @auth
                        @if (auth()->user()->is($user))
                            <a href="{{ route('profile:inbox') }}" class="btn btn-shadcn shadow-sm btn-outline-shadcn">
                               <x-heroicon-s-inbox class="icon color-green me-1" style="width: 18px;"/> Mijn inbox

                                @if($user->unreadMessagesCount() > 0)
                                    <span class="ms-1 badge badge-gray">{{ $user->unreadMessagesCount() }}</span>
                                 @endif
                            </a>

                            @if (active('account:reputation'))
                                <a href="{{ route('account:public', $user) }}" class="btn btn-shadcn shadow-sm btn-outline-shadcn">
                                    <x-tabler-user-circle class="icon color-green me-1" style="width: 18px;"/> Openbaar profiel
                                </a>
                            @else
                                <a href="{{ route('account:reputation') }}" class="btn btn-shadcn shadow-sm btn-outline-shadcn">
                                    <x-tabler-script class="icon color-green me-1" style="width: 18px;"/> Reputatie dashboard
                                </a>
                            @endif
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
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-5">
        <div class="col-lg-3 minimal-sidebar compact-sidebar">

            <!-- Expanded User Stats Section -->
            <div class="mb-4 px-2">
                <h6 class="text-dark fw-bold mb-3" style="font-size: 0.9rem;">Activiteit</h6>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="stat-label">Reputatie</span>
                    <span class="stat-value fw-semibold text-dark">{{  $user->reputation }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="stat-label">Inzendingen</span>
                    <span class="stat-value text-dark">{{ $suggestionCount }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="stat-label">Publicaties</span>
                    <span class="stat-value text-dark">{{ $publications }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="stat-label">Correcties</span>
                    <span class="stat-value text-dark">{{ $correctionsCount }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="stat-label">Lid sinds</span>
                    <span class="stat-value text-muted">{{ $user->created_at ? $user->created_at->translatedFormat('d F, Y') : 'Onbekend' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-label">Laatst gezien</span>
                    <span class="stat-value text-muted">{{ $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Onbekend' }}</span>
                </div>
            </div>

            @if($user->website || $user->bluesky || $user->twitter)
                <hr class="minimal-hr">
                <div class="mb-3">
                    <!-- Cleaned up header without inline styles -->
                    <h6 class="text-dark fw-bold mb-2 px-2 fs-6">Links</h6>

                    <!-- Increased gap slightly to gap-1 for better hover separation -->
                    <nav class="nav flex-column gap-1">
                        @if ($user->website)
                            <a href="{{ $user->website }}" target="_blank" rel="noopener noreferrer" class="minimal-sidenav-link d-flex align-items-center" title="{{ $user->website }}">
                                <x-heroicon-s-globe-alt class="icon" />
                                <!-- Changed to 'Website' for consistency with the other platforms -->
                                <span class="text-truncate">Website</span>
                            </a>
                        @endif

                        @if ($user->bluesky)
                            <a href="https://bsky.app/profile/{{ $user->bluesky }}" target="_blank" rel="noopener noreferrer" class="minimal-sidenav-link d-flex align-items-center" title="Bluesky Profiel">
                                <x-tabler-brand-bluesky class="icon"/>
                                <span class="text-truncate">Bluesky</span>
                            </a>
                        @endif

                        @if ($user->twitter)
                            <a href="https://www.x.com/{{ ltrim($user->twitter, '@') }}" target="_blank" rel="noopener noreferrer" class="minimal-sidenav-link d-flex align-items-center" title="Twitter Profiel">
                                <x-tabler-brand-x class="icon"/>
                                <span class="text-truncate">Twitter</span>
                            </a>
                        @endif
                    </nav>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            @if (! active('account:reputation'))
                <x-heatmap :contributionData="$contributionData" />

                <ul class="nav nav-tabs minimal-tabs mt-4 d-flex align-items-end">
                    <li class="nav-item">
                        <a class="nav-link {{ active('account:public') }}" aria-current="page" href="{{ route('account:public', $user) }}">
                            <x-tabler-world-map class="icon me-1 color-green"/> Publicaties
                        </a>
                    </li>

                    @if (auth()->user()->is($user))
                        <li class="nav-item">
                            <a class="nav-link {{ active('bookmarks:index') }}" href="{{ route('bookmarks:index') }}">
                                <x-tabler-bookmarks class="icon me-1 color-green" /> Bewaarde woorden
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active('word-lists:*') }}" href="{{ route('word-lists:index') }}">
                                <x-tabler-list-details class="icon me-1 color-green"/> Mijn themalijsten
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active('suggestions:index') }}" href="{{ route('suggestions:index') }}">
                                <x-tabler-vocabulary class="icon me-1 color-green"/> Mijn bijdrages
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ active('concepts:index') }}" href="{{ route('concepts:index') }}">
                                <x-tabler-sketching class="icon me-1 color-green"/> Concepten
                            </a>
                        </li>
                    @endif

                    <!-- Right-aligned Create Button -->
                    <li class="nav-item ms-auto mb-2">
                        {{ $action ?? '' }}
                    </li>
                </ul>
            @endif

            <div class="tab-content mt-4" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    {{ $slot }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
