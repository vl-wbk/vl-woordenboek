@extends ('layouts.application-blank', ['title' => 'Nieuws', 'paddingContent' => 'py-0'])

@section ('content')
    <header class="py-5 bg-light border-border-0 mb-4">
        <div class="container">
            <div class="text-center my-5">
                <h1 class="fw-bolder color-green">Nieuws uit het Vlaams Woordenboek</h1>
                <p class="lead mb-0">Blijf op de hoogte van recente toevoegingen, taalkundige inzichten en verrijkingen uit het Vlaams Woordenboek.</p>
            </div>
        </div>
    </header>

    <div class="container">
            <div class="row">
                <!-- Blog entries-->
                <div class="col-lg-8">
                    @forelse ($posts as $post)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="small text-muted">
                                    {{ __('Gepubliceerd door :author op :date', ['author' => $post->author->name, 'date' => $post->created_at->locale('nl_BE')->isoFormat('DD MMMM YYYY HH:mm') ]) }}

                                    @if ($post->category()->exists())
                                        <div class="float-end">
                                        <span class="badge badge-primary">{{ $post->category->name }}</span>
                                    </div>
                                    @endif
                                </div>
                                <h3 class="card-title color-green mb-3">{{  $post->title }}</h3>
                                <p class="card-text mb-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis animi vero voluptate voluptatibus possimus, veniam magni quis!</p>

                                <span class="float-start">
                                    <a class="card-link" href="#!">Lees verder →</a>
                                </span>
                            </div>
                        </div>
                    @empty
                    @endforelse


                    <!-- Pagination-->
                    @if ($posts->hasPages())
                        <hr class="mb-3">
                        {{ $posts->links() }}
                    @endif

                </div>
                <!-- Side widgets-->
                <div class="col-lg-4">
                    <!-- Search widget-->
                    <div class="card mb-4">
                        <div class="card-header">Search</div>
                        <div class="card-body">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Enter search term..." aria-label="Enter search term..." aria-describedby="button-search" />
                                <button class="btn btn-primary" id="button-search" type="button">Go!</button>
                            </div>
                        </div>
                    </div>

                    <!-- Categories widget-->
                    <h5 class="border-bottom pb-2 border-green color-green fw-bold">Categorieen</h4>

                    <div class="border-bottom pb-2 border-green">
                        @foreach ($categories as $category)
                            <a href="" class="badge badge-primary shadow-sm text-decoration-none">
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
