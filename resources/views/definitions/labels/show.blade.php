@extends ('layouts.application-blank', ['title' => 'Label informatie'])

@section ('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 border-bottom border-green pb-2">
            <div>
                <h1 class="display-5 d-flex align-items-center justify-content-between">
                    <span>Label: <span class="color-green">{{ $label->name }}</span></span>
                </h1>
                <div class="pb-3">
                    <span class="badge shadow-sm bg-info fs-6 me-2">
                        <x-heroicon-s-book-open class="icon shadow-sm me-1"/>{{ $relatedArticles->total() }} Woorden
                    </span>

                    @if ($popularWord)
                        <a href="{{ route('word-information.show', $popularWord) }}" class="badge bg-danger text-white text-decoration-none text-dark fs-6">
                            <x-heroicon-s-document-text class="icon me-1"/>Populairste woord: <strong class='ps-1'>{{ $popularWord->word }}</strong>
                        </a>
                    @endif
                </div>
                <p class="text-muted pb-2">{{ $label->description }}</p>
                <small class="text-secondary">Aangemaakt op {{ $label->created_at->locale('nl_BE')->isoFormat('DD MMMM YYYY') }} | Laatst bijgewerkt op {{ $label->updated_at->locale('nl_BE')->isoFormat('DD MMMM YYYY') }}</small>
            </div>
        </div>

        <section class="mb-5">
            <h2 class="mb-4 text-gold">Dit taalkundig label in cijfers</h2>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card information-statistic text-white border-0 shadow-information-statistic p-1">
                        <div class="card-body">
                            <h5 class="card-title">Weergaves</h5>
                            <p class="display-6">{{ $analytics['views']['statistic'] }}</p>
                            <small class="pt-2">{{ $analytics['views']['altText'] }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card information-statistic text-white border-0 shadow-information-statistic p-1">
                        <div class="card-body">
                            <h5 class="card-title">Gekoppelde woorden</h5>
                            <p class="display-6">{{ $analytics['word']['statistic'] }}</p>
                            <small class="pt-2">{{ $analytics['word']['altText'] }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card information-statistic text-white border-0 shadow-information-statistic p-1">
                        <div class="card-body">
                            <h5 class="card-title">Unieke auteurs</h5>
                            <p class="display-6">{{ $analytics['contributor']['statistic'] }}</p>
                            <small class="pt-2">{{ $analytics['contributor']['altText'] }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card information-statistic text-white border-0 shadow-information-statistic p-1">
                        <div class="card-body">
                            <h5 class="card-title">Aantal meldingen</h5>
                            <p class="display-6">{{ $analytics['report']['statistic'] }}</p>
                            <small class="pt-2">{{ $analytics['report']['altText'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        {{-- Technische grammaticale info --}}
        <section class="mb-5">
            <h2 class="mb-4 text-gold">Technische informatie </h2>
            <ul class="list-group border-0 shadow-sm">
                <li class="list-group-item">
                    <strong>Type:</strong>

                    @if ($label->type)
                        <span class="float-end">{{ $label->type }}</span>
                    @else
                        <span class="float-end text-muted">(niet opgegeven)</span>
                    @endif
                </li>
                <li class="list-group-item">
                    <strong>Aangemaakt op:</strong> <span class="float-end">{{ $label->created_at->locale('nl_BE')->isoFormat('DD MMMM YYYY') }}</span>
                </li>
                <li class="list-group-item">
                    <strong>Laatste bewerking:</strong> <span class="float-end">{{ $label->updated_at->locale('nl_BE')->isoFormat('DD MMMM YYYY') }}</span>
                </li>
            </ul>
        </section>

        {{-- Zoekfunctie gekoppelde woorden --}}
        <section class="mb-5">
            @if ($relatedArticles->total() > 0)
                <h2 class="mb-4 text-gold">Gekoppelde woorden</h2>

                <div class="card border-0 shadow-sm">
                    <div class="card-header border-bottom-0 information-statistic">
                        <form action="#woorden" method="GET" class="row g-2">
                            <div class="col-9">
                                <input type="text" name="zoekterm" value="{{ request()->get('zoekterm') }}" class="shadow-sm form-control w-100" autocomplete="off" placeholder="Zoek op woord of sleutelwoorden…">
                            </div>
                            <div class="col-2">
                                <select name="sortering" class="form-select shadow-sm">
                                    <option value="" @selected(request('sortering') === null)>Sorteren op</option>
                                    <option value="alfabetisch" @selected(request('sortering') === 'alfabetisch')>Alfabetische volgorde</option>
                                    <option value="populariteit" @selected(request('sortering') === 'populariteit')>Populariteit</option>
                                    <option value="recent" @selected(request('sortering') === 'recent')>Meest recent</option>
                                </select>
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn w-100 shadow-sm btn-filter">
                                    <x-heroicon-o-funnel class="icon me-1"/> Filteren
                                </button>
                            </div>

                            @if (request()->filled('zoekterm') || request()->filled('sortering'))
                                <div class="col-12">
                                    @if (request()->filled('zoekterm'))
                                        <a href="{{ request()->fullUrlWithoutQuery('zoekterm') }}" class="card-link text-decoration-none py-3 text-danger">
                                            <x-heroicon-s-x-circle class="icon me-1"/> Zoekterm verwijderen
                                        </a>
                                    @endif

                                    @if (request()->filled('sortering'))
                                        <a href="{{ request()->fullUrlWithoutQuery('sortering') }}" class="card-link text-decoration-none py-3 text-danger">
                                            <x-heroicon-s-x-circle class="icon me-1"/> Sortering resetten
                                        </a>
                                    @endif

                                    @if (request()->filled('sortering') && request()->filled('zoekterm'))
                                        <a href="{{ url()->current() }}" class="card-link text-decoration-none py-3 text-danger">
                                            <x-heroicon-s-x-circle class="icon me-1"/> Beide resetten
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </form>
                    </div>

                    <div id="woorden" class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-top-0 color-green">Woord</th>
                                        <th class="border-top-0 color-green">Weergaves</th>
                                        <th class="border-top-0 color-green" colspan="2">Beschrijving</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($relatedArticles as $relatedArticle)
                                        <tr>
                                            <th scope="row" class="fst-italic border-0">{{  $relatedArticle->word }}</th>
                                            <td class="border-0"><x-heroicon-s-eye class="icon color-green me-1"/>{{ toHumanReadableNumber($relatedArticle->views) }}</td>
                                            <td class="border-0">{{ strip_tags(str($relatedArticle->description)->markdown()->sanitizeHtml()->words(15)) }}</td>

                                            <td class="border-0">
                                                <span class="float-end">
                                                    <a href="{{ route('word-information.show', $relatedArticle) }}" class="text-decoration-none text-muted">
                                                        <x-heroicon-o-eye class="icon"/> bekijken
                                                    </a>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white">
                        <div class="float-start text-dark d-sm-none d-md-block">
                            Toont {{ $relatedArticles->firstItem() ?? 0 }} tot {{ $relatedArticles->lastItem() ?? 0 }} van de {{ $relatedArticles->total() }} resultaten
                        </div>

                        <div class="justify-content-end">
                            {{ $relatedArticles->onEachSide(1)->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="card bg-sidenav border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <x-heroicon-o-book-open class="icon color-green icon-blankslate pb-3"/>
                        <h5 class="card-title fw-bold">Geen gekoppelde woorden gevonden</h5>

                        <p class="card-text text-muted">
                            Momenteel zijn er geen woorden gekoppeld of gevonden die matchen in het label. Kom later nog eens terug.
                        </p>

                        @if (request()->filled('zoekterm'))
                            <a href="{{ url()->current() }}" class="btn-submit btn-sm mt-3 btn shadow-sm">
                                <x-heroicon-o-x-circle class="icon me-1"/> Filter ongedaan maken
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection
