@extends ('layouts.application-blank', ['title' => 'Nieuws', 'paddingContent' => 'py-0'])

@section ('content')
    <header class="py-5 bg-light border-border-0 mb-4 shadow-sm">
        <div class="container">
            <div class="text-center my-5">
                @if (active('news:index'))
                    <h1 class="fw-bolder color-green">
                        <x-heroicon-o-newspaper class="icon icon-news-heading"/> Artikelen uit het Vlaams Woordenboek
                    </h1>
                @elseif (active('categories:show'))
                    <h1 class="fw-bolder color-green">
                        <x-heroicon-o-tag class="icon icon-news-heading"/> {{ $category->name }}
                    </h1>
                @endif

                <p class="lead mb-0">
                    @if (active('news:index'))
                        Blijf op de hoogte van recente toevoegingen, taalkundige inzichten en verrijkingen uit het Vlaams Woordenboek.
                    @elseif (active('categories:show'))
                        {{  $category->description }}
                    @endif
                </p>

                @if (active('categories:show'))
                    <a href="{{ route('news:index') }}" class="btn btn-submit mt-3 border-0 shadow-sm">
                        Terug naar overzicht
                    </a>
                @endif
            </div>
        </div>
    </header>

    <div class="container">
            <div class="row">
                <!-- Blog entries-->
                <div class="col-lg-8">
                    @if (request()->has('zoekterm') && request()->get('zoekterm') !== null)
                        <h5 class="color-green border-bottom pb-2 border-green mb-3">
                            Resulaten voor de zoekterm: <span class="text-dark fst-italic fw-bold">{{ request()->get('zoekterm') }}</span>
                        </h5>
                    @endif

                    @forelse ($posts as $post)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="small text-muted">
                                    {{ __('Gepubliceerd door :author op :date', ['author' => $post->author->name, 'date' => $post->created_at->locale('nl_BE')->isoFormat('DD MMMM YYYY HH:mm') ]) }}

                                    <div class="float-end">
                                        <span class="text-muted">{{ $post->read_time }}</span>
                                    </div>
                                </div>
                                <h3 class="card-title color-green mb-3">{{  $post->title }}</h3>
                                <p class="card-text mb-2">{!! strip_tags(str($post->content)->words(75)->markdown()->sanitizeHtml()) !!}</p>

                                <span class="float-start">
                                    <a class="card-link text-decoration-none" href="{{ route('news:show', $post) }}">
                                        <x-tabler-eyeglass-2 class="icon me-1"/> Lees verder →
                                    </a>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="card bg-sidenav border-0 shadow-sm text-center">
                            <div class="card-body p-4">
                                <x-heroicon-o-newspaper class="icon color-green icon-blankslate pb-3"/>
                                <h5 class="card-title fw-bold">Geen artikelen gevonden!</h5>

                                <p class="card-text text-muted mb-3">
                                    Momenteel zijn er geen artikelen in het Vlaams Woordenboek te vinden die voldoen aan je zoekopdracht of categorie. Probeer een andere zoekterm of kom later nog eens terug.
                                </p>

                                @if (request()->has('zoekterm') && request()->get('zoekterm') !== null)
                                    <a href="{{ route('news:index') }}" class="btn btn-sm btn-submit border-0">
                                        <x-heroicon-o-x-mark class="icon me-1"/> Zoekopdracht ongedaan maken
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforelse


                    <!-- Pagination-->
                    @if ($posts->hasPages())
                        <hr class="mb-3">
                        {{ $posts->appends(request()->query())->links() }}
                    @endif

                </div>
                <!-- Side widgets-->
                <div class="col-lg-4">
                    <!-- Search widget-->
                    <h5 class="border-bottom pb-2 border-green color-green fw-bold"><x-heroicon-o-magnifying-glass-circle class="icon me-1"/> Artikel opzoeken</h4>

                    <form action="{{ route('news:index') }}" method="GET" class="mb-4 border-0 shadow-sm">
                        <div class="input-group">
                            <input class="form-control" type="text" name="zoekterm" placeholder="Zoek op de titel van het artikel..." aria-label="Zoek op de titel van het artikel..." value="{{ request()->get('zoekterm') }}" aria-describedby="button-search" />
                            <button class="btn btn-submit" id="button-search" type="submit">
                                <x-heroicon-s-magnifying-glass class="icon me-1"/> Zoek
                            </button>
                        </div>
                    </form>

                    <!-- Categories widget-->
                    <h5 class="border-bottom pb-2 border-green color-green fw-bold"><x-heroicon-s-tag class="icon me-1"/> Categorieen</h4>

                    <div class="border-bottom pb-2 border-green">
                        @foreach ($categories as $category)
                            <a href="{{ route('categories:show', $category) }}" class="badge badge-primary shadow-sm text-decoration-none">
                                {{ $category->name }} <span class="fst-italic fw-bold">({{ $category->posts->count() }})</span>
                            </a>
                        @endforeach
                    </div>

                    <a href="{{  url('feed') }}" class="btn mt-2 w-100 text-white shadow-sm btn-rss">
                        <x-heroicon-s-rss class="icon me-1"/> RSS Feed
                    </a>

                </div>
            </div>
        </div>
@endsection
