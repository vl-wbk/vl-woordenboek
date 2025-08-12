@extends ('layouts.application-blank', ['title' => 'Nieuws'])

@section('content')
    <header class="bg-light border-border-0 mb-4 py-5 shadow-sm">
        <div class="container">
            <div class="my-5 text-center">
                @if (active('news:index'))
                    <h1 class="fw-bolder color-green">
                        <x-heroicon-o-newspaper class="icon icon-news-heading" /> Nieuwsberichten van het Vlaams Woordenboek
                    </h1>
                @elseif (active('categories:show'))
                    <h1 class="fw-bolder color-green">
                        <x-heroicon-o-tag class="icon icon-news-heading" /> {{ $category->name }}
                    </h1>
                @endif

                <p class="lead mb-0">
                    @if (active('news:index'))
                        Blijf op de hoogte van recente toevoegingen, taalkundige inzichten en verrijkingen uit het Vlaams
                        Woordenboek.
                    @elseif (active('categories:show'))
                        {{ $category->description }}
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
                    <h5 class="color-green border-bottom border-green mb-3 pb-2">
                        Resulaten voor de zoekterm: <span
                            class="text-dark fst-italic fw-bold">{{ request()->get('zoekterm') }}</span>
                    </h5>
                @endif

                @forelse ($posts as $post)
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="small text-muted">
                                {{ __('Gepubliceerd door :author op :date', ['author' => $post->author->name, 'date' => $post->created_at->locale('nl_BE')->isoFormat('DD MMMM YYYY HH:mm')]) }}

                                <div class="float-end">
                                    <span class="text-muted">{{ $post->read_time }}</span>
                                </div>
                            </div>
                            <h3 class="card-title color-green mb-3">{{ $post->title }}</h3>
                            <p class="card-text mb-2">{!! strip_tags(str($post->content)->words(75)->markdown()->sanitizeHtml()) !!}</p>

                            <span class="float-start">
                                <a class="card-link text-decoration-none" href="{{ route('news:show', $post) }}">
                                    <x-tabler-eyeglass-2 class="icon me-1" /> Lees verder →
                                </a>
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="card bg-sidenav border-0 text-center shadow-sm">
                        <div class="card-body p-4">
                            <x-heroicon-o-newspaper class="icon color-green icon-blankslate pb-3" />
                            <h5 class="card-title fw-bold">Geen nieuwsberichten gevonden!</h5>

                            <p class="card-text text-muted mb-3">
                                Momenteel zijn er geen nieuwsberichten in het Vlaams Woordenboek te vinden die voldoen aan
                                je zoekopdracht of categorie. Probeer een andere zoekterm of kom later nog eens terug.
                            </p>

                            @if (request()->has('zoekterm') && request()->get('zoekterm') !== null)
                                <a href="{{ route('news:index') }}" class="btn btn-sm btn-submit border-0">
                                    <x-heroicon-o-x-mark class="icon me-1" /> Zoekopdracht ongedaan maken
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
                <h5 class="border-bottom border-green color-green fw-bold pb-2"><x-heroicon-o-magnifying-glass-circle
                        class="icon me-1" /> Nieuwsbericht opzoeken</h5>

                <form action="{{ route('news:index') }}" method="GET" class="mb-4 border-0 shadow-sm">
                    <div class="input-group">
                        <input class="form-control" type="text" name="zoekterm"
                            placeholder="Zoek op de titel van het nieuwsbericht..."
                            aria-label="Zoek op de titel van het nieuwsbericht..." value="{{ request()->get('zoekterm') }}"
                            aria-describedby="button-search" />
                        <button class="btn btn-submit" id="button-search" type="submit">
                            <x-heroicon-s-magnifying-glass class="icon me-1" /> Zoek
                        </button>
                    </div>
                </form>

                <!-- Categories widget-->
                <h5 class="border-bottom border-green color-green fw-bold pb-2"><x-heroicon-s-tag class="icon me-1" />
                    Categorieen</h5>

                <div class="border-bottom border-green pb-2">
                    @foreach ($categories as $category)
                        <a href="{{ route('categories:show', $category) }}"
                            class="badge badge-primary text-decoration-none shadow-sm">
                            {{ $category->name }} <span
                                class="fst-italic fw-bold">({{ $category->posts->count() }})</span>
                        </a>
                    @endforeach
                </div>

                <div class="row">
                    <div class="@if (auth()->check() && auth()->user()->can('submit-post', \App\Models\Blog::class)) col-6 @else col-12 @endif">
                        <a href="{{ url('feed') }}" class="btn w-100 btn-rss mt-2 text-white shadow-sm">
                            <x-heroicon-s-rss class="icon me-1" /> RSS Feed
                        </a>
                    </div>

                    @can('submit-post', \App\Models\Blog::class)
                        <div class="col-6">
                            <a href="" class="btn w-100 btn-light mt-2 border-0 shadow-sm">
                                <x-heroicon-o-pencil-square class="icon color-green me-1" /> Uw artikel hier?
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
