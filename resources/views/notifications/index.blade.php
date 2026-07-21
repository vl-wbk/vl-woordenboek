@extends('layouts.application-blank', ['title' => 'Meldingen — ' . auth()->user()->name, 'paddingContent' => 'pb-4 mb-5'])

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

    body { overflow-x: hidden; -webkit-font-smoothing: antialiased; }

    .card-shadcn {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.05);
    }

    .avatar {
        width: 80px; height: 80px;
        background-color: var(--muted);
        border: 1px solid var(--border);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: 600;
        overflow: hidden;
    }

    .btn-shadcn {
        font-size: 0.875rem; font-weight: 500;
        border-radius: var(--radius);
        padding: 0.5rem 1rem;
        transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-dark-shadcn  { background: var(--ring); color: #fff; border: 1px solid var(--muted); }
    .btn-dark-shadcn:hover { background: #27272a; color: #fff; }
    .btn-outline-shadcn { background: #fff; border: 1px solid var(--border); color: var(--foreground); }
    .btn-outline-shadcn:hover { background: var(--muted); }
    .btn-xs { font-size: 0.78rem; padding: 0.3rem 0.65rem; }

    /* Sidenav */
    .sidenav-label {
        font-size: 0.70rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .05em;
        color: var(--muted-foreground);
        margin-bottom: .75rem; padding-left: .5rem;
    }
    .sidenav-link {
        display: flex; align-items: center; gap: .75rem;
        padding: .5rem .75rem;
        font-size: .875rem; color: var(--foreground);
        text-decoration: none;
        border-radius: var(--radius);
        transition: background .2s;
    }
    .sidenav-link:hover, .sidenav-link.active { background: #fff; color: var(--foreground); }
    .sidenav-link.active { font-weight: 600; }
    .sidenav-link i, .sidenav-link svg { width: 16px; height: 16px; color: var(--muted-foreground); }
    .sidenav-count {
        margin-left: auto; background: #2C2E31; color: #fff;
        font-size: .6rem; font-weight: 600;
        padding: 1px 6px; border-radius: 999px; min-width: 18px; text-align: center;
    }

    .search-input-shadcn {
        font-size: .875rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: .4rem .75rem;
        background: #fff; width: 100%;
        transition: border-color .2s;
    }
    .search-input-shadcn:focus { outline: none; border-color: var(--ring); }

    /* Stats row */
    .stat-card {
        display: flex; align-items: center; justify-content: space-between;
        padding: .875rem 1rem;
    }
    .stat-icon {
        width: 36px; height: 36px; border-radius: 8px;
        background: #eff6ff; color: #2563eb;
        display: flex; align-items: center; justify-content: center;
    }
    .stat-icon svg { width: 18px; }

    /* Tabs */
    .nav-tabs-shadcn { border-bottom: 1px solid var(--border); margin-bottom: 0; }
    .nav-tabs-shadcn .nav-link {
        border: none; color: var(--muted-foreground);
        border-bottom: 2px solid transparent;
        padding: .5rem 1rem; font-size: .875rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .nav-tabs-shadcn .nav-link.active {
        color: var(--foreground); border-bottom-color: var(--foreground); background: none;
    }
    .nav-tabs-shadcn .tab-pill {
        background: #2C2E31; color: #fff;
        font-size: .58rem; font-weight: 600;
        padding: 1px 6px; border-radius: 999px;
    }

    /* Notification rows */
    .n-row {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }
    .n-row:last-child { border-bottom: none; }
    .n-row:hover { background: #fafafa; }
    .n-row.unread { background: #f7f9ff; }
    .n-row.unread:hover { background: #eef2ff; }

    .n-dot { width: 7px; height: 7px; border-radius: 50%; background: #3b82f6; flex-shrink: 0; margin-top: 6px; }
    .n-dot-ph { width: 7px; flex-shrink: 0; }

    .n-icon {
        width: 38px; height: 38px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .n-icon svg { width: 17px; }
    .ic-blue   { background: #eff6ff; color: #2563eb; }
    .ic-green  { background: #f0fdf4; color: #16a34a; }
    .ic-purple { background: #faf5ff; color: #9333ea; }
    .ic-amber  { background: #fffbeb; color: #d97706; }
    .ic-red    { background: #fef2f2; color: #dc2626; }
    .ic-gray   { background: var(--muted); color: var(--muted-foreground); }

    .n-body { flex: 1; min-width: 0; }
    .n-title { font-size: .855rem; font-weight: 500; line-height: 1.4; margin-bottom: 3px; }
    .n-desc  { font-size: .79rem; color: var(--muted-foreground); margin-bottom: 5px; line-height: 1.4; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 480px; }
    .n-meta  { display: flex; align-items: center; gap: 6px; font-size: .7rem; color: var(--muted-foreground); }
    .n-meta svg { width: 11px; }

    .n-badge {
        font-size: .62rem; font-weight: 600; text-transform: uppercase;
        padding: 2px 6px; border-radius: 4px; border: 1px solid;
        display: inline-block;
    }
    .nb-blue   { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
    .nb-green  { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
    .nb-purple { background: #faf5ff; color: #7e22ce; border-color: #e9d5ff; }
    .nb-amber  { background: #fffbeb; color: #92400e; border-color: #fde68a; }
    .nb-red    { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
    .nb-gray   { background: var(--muted); color: var(--muted-foreground); border-color: var(--border); }

    .n-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; margin-left: 8px; }

    .btn-icon-ghost {
        background: none; border: 1px solid var(--border); border-radius: 6px;
        padding: 4px 6px; cursor: pointer;
        color: var(--muted-foreground); display: flex; align-items: center;
        transition: all .15s;
    }
    .btn-icon-ghost:hover { background: var(--muted); color: var(--foreground); }
    .btn-icon-ghost svg { width: 14px; }

    /* Empty state */
    .empty-state {
        text-align: center; padding: 56px 24px;
        color: var(--muted-foreground);
    }
    .empty-state svg { width: 42px; opacity: .3; display: block; margin: 0 auto 14px; }

    /* Pagination */
    .pagination .page-link {
        border: 1px solid var(--border); color: var(--foreground);
        font-size: .875rem; border-radius: 4px; margin: 0 2px;
    }
    .pagination .page-item.active .page-link {
        background: var(--ring); color: #fff; border-color: var(--ring);
    }

    .social-link-compact {
        display: flex; align-items: center; gap: 8px;
        padding: 4px 8px; font-size: .875rem; color: var(--foreground);
        text-decoration: none; border-radius: 6px; transition: all .1s;
    }
    .social-link-compact:hover { background: #fff; color: var(--foreground); }
    .social-icon-sm { width: 16px !important; height: 16px !important; flex-shrink: 0; opacity: .6; }
    .social-link-compact:hover .social-icon-sm { opacity: 1; }
</style>

@php
    $activeTab = request()->input('tab', 'all');
@endphp

<div class="container-fluid py-4">
    <div class="row justify-content-center pt-3">
        <div class="col-lg-11">

            {{-- Profile header --}}
            <div class="d-flex align-items-center gap-4 mb-5">
                <img src="{{ auth()->user()->getFilamentAvatarUrl() }}" loading="lazy" class="avatar shadow-sm"/>
                <div class="flex-grow-1">
                    <h2 class="fw-bold mb-0">{{ auth()->user()->name }}</h2>
                    <p class="text-muted small mb-2">{{ auth()->user()->bio ?? 'Gebruiker van het Vlaams Woordenboek' }}</p>
                    <div class="d-flex gap-2 text-uppercase fw-bold" style="font-size: .80rem;">
                        @if (auth()->user()->user_type->in(enums: [\App\UserTypes::Developer, \App\UserTypes::Administrators]))
                            <span class="badge rounded-pill border text-secondary bg-secondary bg-opacity-10 px-2 py-1">
                                <x-tabler-users class="icon me-1"/> Kernlid
                            </span>
                        @elseif(auth()->user()->user_type->in(enums: [\App\UserTypes::Editor, \App\UserTypes::EditorInChief]))
                            <span class="badge rounded-pill border text-secondary bg-secondary bg-opacity-10 px-2 py-1">
                                <x-tabler-users class="icon me-1"/> Redactie
                            </span>
                        @endif

                        @if (auth()->user()->hasVerifiedEmail())
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
                <div class="d-none d-md-flex gap-2 align-items-center">
                    @if($tabCounts['unread'] > 0)
                        <form method="POST" action="{{ route('notifications:readAll') }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-shadcn shadow-sm btn-outline-shadcn">
                                <x-heroicon-o-check-circle class="icon text-success me-1" style="width:18px;"/> Alles gelezen
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('notifications:destroyAll') }}"
                          onsubmit="return confirm('Weet je zeker dat je alle meldingen wilt verwijderen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-shadcn shadow-sm btn-outline-shadcn">
                            <x-heroicon-o-trash class="icon text-danger me-1" style="width:18px;"/> Wis alles
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="row justify-content-center g-5">

        {{-- Sidebar --}}
        <div class="col-lg-2">
            <div class="mb-3">
                <div class="sidenav-label">
                    <x-heroicon-s-magnifying-glass class="icon-sm me-1"/> Zoeken
                </div>
                <form method="GET" action="{{ request()->url() }}">
                    <input type="text" name="zoekterm" value="{{ request()->input('zoekterm') }}" class="search-input-shadcn" placeholder="Woord zoeken...">
                </form>
            </div>

            <hr>

            <div class="mb-4">
                <nav class="nav flex-column">
                    <a href="{{ route('account:public', auth()->user()) }}" class="sidenav-link {{ active('account:public') }}">
                        <x-heroicon-o-globe-europe-africa class="icon color-green"/>
                        <span class="flex-grow-1">Publicaties</span>
                    </a>
                    <a href="{{ route('bookmarks:index') }}" class="sidenav-link {{ active('bookmarks:index') }}">
                        <x-heroicon-o-bookmark class="icon color-green"/>
                        <span class="flex-grow-1">Bewaarde woorden</span>
                    </a>
                    <a href="{{ route('concepts:index') }}" class="sidenav-link {{ active(['concepts:index','concepts:create']) }}">
                        <x-heroicon-o-clipboard-document-list class="icon color-green"/>
                        <span class="flex-grow-1">Concepten</span>
                    </a>
                </nav>
            </div>

            <div class="mb-4">
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

            <div class="mb-4">
                <div class="sidenav-label">Account</div>
                <nav class="nav flex-column">
                    <a href="{{ route('notifications:index') }}" class="sidenav-link {{ active('notifications:index') }}">
                        <x-heroicon-o-bell class="icon color-green"/>
                        <span class="flex-grow-1">Meldingen</span>
                        @if($tabCounts['unread'] > 0)
                            <span class="sidenav-count">{{ $tabCounts['unread'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('account:reputation') }}" class="sidenav-link {{ active('account:reputation') }}">
                        <x-heroicon-o-queue-list class="icon color-green"/>
                        <span class="flex-grow-1">Reputatie</span>
                    </a>
                </nav>
            </div>

            @if(auth()->user()->website || auth()->user()->bluesky || auth()->user()->twitter)
                <hr>
                <div class="sidenav-label mb-2">Socials</div>
                <nav class="nav flex-column gap-1">
                    @if(auth()->user()->website)
                        <a href="{{ auth()->user()->website }}" class="social-link-compact">
                            <x-heroicon-s-globe-alt class="social-icon-sm"/>
                            <span class="text-truncate">{{ auth()->user()->website }}</span>
                        </a>
                    @endif
                    @if(auth()->user()->bluesky)
                        <a href="https://bsky.app/profile/{{ auth()->user()->bluesky }}" class="social-link-compact">
                            <x-tabler-brand-bluesky class="social-icon-sm"/>
                            <span>Bluesky</span>
                        </a>
                    @endif
                    @if(auth()->user()->twitter)
                        <a href="https://www.x.com/{{ ltrim(auth()->user()->twitter,'@') }}" class="social-link-compact">
                            <x-tabler-brand-x class="social-icon-sm"/>
                            <span>Twitter</span>
                        </a>
                    @endif
                </nav>
            @endif
        </div>

        {{-- Main content --}}
        <div class="col-lg-9">


            {{-- Tabs --}}
            <ul class="nav nav-tabs-shadcn">
                @foreach($tabs as $tab)
                    <li class="nav-item">
                        <a href="{{ request()->fullUrlWithQuery(['tab' => $tab['key'], 'page' => null]) }}"
                           class="nav-link {{ $activeTab === $tab['key'] ? 'active' : '' }}">
                            {{ $tab['label'] }}
                            @if(isset($tabCounts[$tab['key']]) && $tabCounts[$tab['key']] > 0)
                                <span class="tab-pill">{{ $tabCounts[$tab['key']] }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Notification list --}}
            <div class="card-shadcn" style="border-radius: 0 0 var(--radius) var(--radius);">
                @forelse($notifications as $notification)
                    @php
                        $isUnread  = is_null($notification->read_at);
                        $data      = $notification->data;
                        $type      = $data['type'] ?? 'systeem';
                        $cfg       = $typeConfig[$type] ?? $typeConfig['systeem'];
                    @endphp

                    <div class="n-row {{ $isUnread ? 'unread' : '' }}">
                        {{-- Unread dot --}}
                        @if($isUnread)
                            <span class="n-dot" aria-label="Ongelezen"></span>
                        @else
                            <span class="n-dot-ph"></span>
                        @endif

                        {{-- Type icon --}}
                        <div class="n-icon {{ $cfg['iconClass'] }}">
                            @switch($type)
                                @case('suggesties') <x-heroicon-o-document-check style="width:17px;"/> @break
                                @case('kudos')      <x-heroicon-o-hand-thumb-up style="width:17px;"/> @break
                                @case('reacties')   <x-heroicon-o-chat-bubble-left-ellipsis style="width:17px;"/> @break
                                @case('contact')    <x-heroicon-o-user-plus style="width:17px;"/> @break
                                @default            <x-heroicon-o-bell style="width:17px;"/> @break
                            @endswitch
                        </div>

                        {{-- Body --}}
                        <div class="n-body">
                            <p class="n-title mb-0">{{ $data['title'] ?? 'Nieuwe melding' }}</p>
                            <p class="n-desc mb-2">{{ $data['body'] ?? '' }}</p>
                            <div class="n-meta">
                                <span class="n-badge {{ $cfg['badgeClass'] }}">{{ $cfg['label'] }}</span>
                                <x-heroicon-o-clock style="width:11px;"/>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="n-actions">
                            @if($isUnread && isset($data['url']))
                                <a href="{{ $data['url'] }}" class="btn btn-shadcn btn-outline-shadcn btn-xs">
                                    {{ $data['action_label'] ?? 'Bekijk' }}
                                </a>
                            @endif

                            @if($isUnread)
                                <form method="POST" action="{{ route('notifications:read', $notification->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-icon-ghost shadow-sm" title="Markeer als gelezen">
                                        <x-heroicon-o-check class="text-success" style="width:14px;"/>
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('notifications:destroy', $notification->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon-ghost shadow-sm" title="Verwijder melding">
                                    <x-heroicon-s-trash class="text-danger" style="width:14px;"/>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <x-heroicon-o-bell-slash style="width:42px; opacity:.3; display:block; margin:0 auto 14px;"/>
                        <p class="fw-semibold mb-1" style="font-size:.9rem;">Geen meldingen</p>
                        <p class="small text-muted">Je bent helemaal bijgewerkt.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($notifications->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $notifications->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
