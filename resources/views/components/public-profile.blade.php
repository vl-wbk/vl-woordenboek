@extends('layouts.application-blank', ['title' => $user->name, 'paddingContent' => 'pb-4 mb-5'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-12 pb-2 pb-md-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 flex-wrap gap-2">
                    <h3 class="color-green m-0">Openbaar profiel</h3>

                    @auth
                        <div class="d-flex flex-wrap gap-2 border-0 shadow-sm">
                            @if (auth()->user()->is($user))
                                <a href="{{ route('profile:inbox') }}" class="btn btn-sm btn-light">
                                    <x-heroicon-s-inbox class="icon color-green icon-sm me-1"/> mijn inbox
                                    @if($user->unreadMessagesCount() > 0)
                                        <span class="ms-1 badge badge-gray">{{ $user->unreadMessagesCount() }}</span>
                                    @endif
                                </a>

                                <a href="{{ route('profile.settings') }}" class="btn btn-sm btn-light">
                                    <x-heroicon-o-cog-8-tooth class="icon color-green icon-sm me-1"/> instellingen
                                </a>
                            @endif

                            @if (auth()->user()->isNot($user))
                                <a href="{{ route('inbox:create', ['participant' => $user->id]) }}" class="btn btn-sm btn-light">
                                    <x-heroicon-o-envelope-open class="icon color-green icon-sm me-1"/> bericht gebruiker
                                </a>
                            @endif

                            @if ($contactExist)
                                <form id="storeContact" action="{{ route('contacts:store') }}" method="POST" class="d-none">
                                    @csrf
                                    <input type="text" name="gebruikersnaam" value="{{ $user->name }}">
                                </form>

                                <a href="{{ route('contacts:store') }}" onclick="event.preventDefault(); document.getElementById('storeContact').submit();" class="btn btn-sm btn-light">
                                    <x-heroicon-o-user-plus class="icon color-green icon-sm me-1"/> contact toevoegen
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>

            <div class="col-12 col-lg-3">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="d-flex align-items-center mb-4">
                        <img loading="lazy" src="{{ $user->getFilamentAvatarUrl() }}" class="rounded-circle me-3" height="60" width="60" alt="{{ $user->name }} - gravatar">
                        <div>
                            <h5 class="card-title fw-bold color-green mb-0">{{ $user->name }}</h5>
                            <p class="card-text small text-muted mb-0">Lid sinds: {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    @if($user->bio)
                        <p class="card-text text-muted mb-3">{{ $user->bio }}</p>
                    @endif

                    <div class="{{ ($user->website || $user->twitter || $user->bluesky) ? 'mb-3' : '' }}">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span class="icon-list-item">
                                    <x-heroicon-o-book-open class="icon color-green me-2"/>Artikel bijdragen
                                </span>
                                <span class="badge badge-gray">{{ $suggestedArticleCount->get('total') }}</span>
                            </li>

                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span class="icon-list-item">
                                     <x-heroicon-o-document-text class="icon color-green me-2"/>Etymologische bijdragen
                                </span>
                                <span class="badge badge-gray">{{ $suggestedEtymologiesCount->get('total') }}</span>
                            </li>

                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span class="icon-list-item">
                                    <x-heroicon-o-bell-alert class="icon color-green me-2"/> Meldingen
                                </span>
                                <span class="badge badge-gray">{{ $reportCount->get('total') }}</span>
                            </li>

                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span class="icon-list-item">
                                    <x-heroicon-o-pencil-square class="icon color-green me-2"/>Gast posts
                                </span>
                                <span class="badge badge-gray">{{ $articleCount->get('total') }}</span>
                            </li>

                            <li class="d-flex justify-content-between align-items-center mb-0">
                                <span class="icon-list-item">
                                    <x-heroicon-o-clock class="icon color-green me-2"/>Laatste aanmelding
                                </span>
                                <span class="fst-italic text-muted">{{ optional($user->last_seen_at)->diffForHumans() ?? '-' }}</span>
                            </li>
                        </ul>
                    </div>

                    @if ($user->twitter || $user->bluesky || $user->website)
                        <div class="d-flex flex-wrap justify-content-start gap-2">
                            @if ($user->website)
                                <a href="{{ $user->website }}" class="btn btn-website text-yellow flex-fill" target="_blank" rel="noopener">
                                    <x-heroicon-o-globe-europe-africa class="icon me-1"/> Website
                                </a>
                            @endif

                            @if ($user->twitter)
                                <a href="https://www.x.com/{{ ltrim($user->twitter, '@') }}" class="btn btn-twitter flex-shrink-0" target="_blank" rel="noopener">
                                    <x-tabler-brand-x class="icon me-1"/>
                                </a>
                            @endif

                            @if ($user->bluesky)
                                <a href="https://bsky.app/profile/{{ $user->bluesky }}" class="btn btn-bluesky flex-shrink-0" target="_blank" rel="noopener">
                                    <x-tabler-brand-bluesky class="icon me-1"/>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-lg-9">
                <div class="overflow-auto">
                    <ul class="nav nav-tabs border-bottom-2 flex-nowrap" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('account:public', $user) }}" class="nav-link {{ active('account:public', 'border-primary border-bottom fw-semibold') }} border-0 border-3 rounded-0 bg-transparent text-dark">
                                <x-heroicon-o-book-open class="icon color-green me-1"/> Artikel bijdragen
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('account:public:etymologies', $user) }}" class="nav-link {{ active('account:public:etymologies', 'border-primary border-bottom fw-semibold') }} border-0 border-3 rounded-0 bg-transparent text-dark">
                                <x-heroicon-o-document-text class="icon color-green me-1"/> Etymologische bijdragen
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="pt-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
@endsection
