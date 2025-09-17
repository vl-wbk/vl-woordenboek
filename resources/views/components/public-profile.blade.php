@extends ('layouts.application-blank', ['title' => $user->name, 'paddingContent' => 'pb-4 mb-5'])

@section('content')
    <div class="container-lg py-5">
        <div class="row">
            <div class="col-lg-4 col-md-5 mx-auto">
                <div class="card border-0 shadow-sm p-4" style="max-width: 500px;">
                    <div class="d-flex align-items-center mb-3">
                        <x-heroicon-s-user-circle class="icon icon-lg me-3 text-muted" style="width: 80px; height: 80px;"/>
                        <div>
                            <h5 class="card-title fw-bold color-green mb-0"> {{ $user->name }}</h5>
                            <p class="card-text small text-muted">Lid sinds: {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    @if($user->bio)
                        <p class="card-text text-muted mb-3">
                            {{ $user->bio }}
                        </p>
                    @endif

                    <div class="{{ ($user->website || $user->twitter || $user->bluesky) ? 'mb-3' : '' }}">
                        <ul class="list-unstyled">
                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span class="icon-list-item">
                                    <x-heroicon-o-book-open class="icon color-green me-2"/>Artikel suggesties
                                </span>
                                <span class="badge badge-gray">
                                    {{ $suggestedArticleCount->get('total') }}
                                </span>
                            </li>

                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span class="icon-list-item">
                                     <x-heroicon-o-document-text class="icon color-green me-2"/>Etymologische suggesties
                                </span>

                                <span class="badge badge-gray">
                                    {{ $suggestedEtymologiesCount->get('total') }}
                                </span>
                            </li>

                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span class="icon-list-item">
                                    <x-heroicon-o-bell-alert class="icon color-green me-2"/> Meldingen
                                </span>

                                <span class="badge badge-gray">
                                    {{ $reportCount->get('total') }}
                                </span>
                            </li>

                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span class="icon-list-item">
                                    <x-heroicon-o-pencil-square class="icon color-green me-2"/>Gast posts
                                </span>

                                <span class="badge badge-gray">
                                    {{ $articleCount->get('total') }}
                                </span>
                            </li>

                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span class="icon-list-item">
                                    <x-heroicon-o-clock class="icon color-green me-2"/>Laatste aanmelding
                                </span>

                                <span class="fst-italic text-muted">
                                    ({{ $user->last_seen_at->diffForHumans() }})
                                </span>
                            </li>
                        </ul>
                    </div>

                    @if ($user->twitter || $user->bluesky || $user->website)
                        <div class="d-flex justify-content-start gap-2">
                            @if ($user->website)
                                <a href="{{ $user->website }}" class="btn w-100 btn-website text-yellow">
                                    <x-heroicon-o-globe-europe-africa class="icon me-1"/> Website
                                </a>
                            @endif

                            @if ($user->twitter)
                                <a href=https://www.x.com/{{ $user->twitter }}"" class="btn btn-twitter w-25">
                                    <x-tabler-brand-x class="icon me-1"/>
                                </a>
                            @endif

                            @if ($user->bluesky)
                                <a href="https://bsky.app/profile/{{ $user->bluesky }}" class="btn btn-bluesky w-25">
                                    <x-tabler-brand-bluesky class="icon me-1"/>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-8 col-md-7">
                <ul class="nav nav-tabs border-bottom-2" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('account:public', $user) }}" class="nav-link {{ active('account:public', 'border-primary border-bottom fw-semibold') }} border-0 border-3 rounded-0 bg-transparent text-dark">
                            <x-heroicon-o-book-open class="icon color-green me-1"/> Gepubliceerde artikelen
                        </a>
                    </li>

                    <li class=nav-item" role="presentation">
                        <a href="{{ route('account:public:etymologies', $user) }}" class="nav-link {{ active('account:public:etymologies', 'border-primary border-bottom fw-semibold') }} border-0 border-3 rounded-0 bg-transparent text-dark">
                            <x-heroicon-o-document-text class="icon color-green me-1"/> Gepubliceerde etymologieën
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a href="{{ route('account:public:articles', $user) }}" class="nav-link {{ active('account:public:articles', 'border-primary border-bottom fw-semibold') }} border-0 border-3 rounded-0 bg-transparent text-dark">
                            <x-heroicon-o-pencil-square class="icon color-green me-1"/> Gepubliceerde nieuwsberichten
                        </a>
                    </li>
                </ul>

                <div class="pt-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
@endsection