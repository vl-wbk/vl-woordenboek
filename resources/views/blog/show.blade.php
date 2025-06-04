@extends('layouts.application-blank', ['title' => trans('Nieuwsartikelen')])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="border-green mb-3 @if ($post->comments_enabled) border-bottom @endif">
                    <header class="mb-4">
                        <h1 class="fw-bolder text-gold mb-1">{{ $post->title }}</h1>
                        <div class="text-muted fst-italic mb-2">{{ __('Gepubliceerd door :author op :date', ['author' => $post->author->name, 'date' => $post->created_at->locale('nl_BE')->isoFormat('DD MMMM YYYY HH:mm') ]) }}</div>

                        <!-- Post categories-->
                        @if ($post->category()->exists())
                            @foreach ($post->category as $category) {{-- Loop through the ctageories that are associated with the article --}}
                                <a href="{{ route('categories:show', $category) }}" class="badge badge-primary shadow-sm text-decoration-none" href="#!">
                                    <x-heroicon-s-tag class="icon icon-sm me-1"/> {{ $category->name }}
                                </a>
                            @endforeach
                        @else  {{-- No ctageories are found so simply return the uncategorised label. --}}
                            <span class="badge badge-primary shadow-sm text-decoration-none">
                                <x-heroicon-s-tag class="icon icon-sm me-1"/> Ongecategoriseerd
                            </span>
                        @endif
                    </header>

                    <!-- Post content-->
                    <section class="article-section mb-4">
                        {!! str($post->content)->markdown()->sanitizeHtml() !!}
                    </section>
                </article>

                <!-- Comments section-->
                @if ($post->comments_enabled)
                    <section class="pb-3">
                    <div class="card bg-light border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center fw-bold">
                            <span class="color-green">Reacties (2)</span>

                            <nav aria-label="Page navigation example">
                                <ul class="pagination border-0 pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">
                                            <x-heroicon-o-chevron-double-left class="icon icon-sm"/> recentere reacties
                                        </a>
                                    </li
                                    >
                                    <li class="page-item">
                                        <a class="page-link" href="#">
                                            oudere reacties <x-heroicon-o-chevron-double-right class="icon icon-sm"/>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-warning-subtle d-flex">
                                    <div class="flex-shrink-0"><img class="rounded-circle shadow-sm" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                    <div class="ms-3">
                                        <div class="fw-bold">
                                            Commenter Name

                                            <span class="ms-1 badge float-end badge-warning fst-italic">
                                                Gerapporteerde reactie - aandacht vereist
                                            </span>
                                        </div>

                                        <span>When I look at the universe and all the ways the universe wants to kill us, I find it hard to reconcile that with statements of beneficence.</span>

                                        <ul class="list-inline pt-2">
                                            <li class="list-inline-item">
                                                <a href="" class="text-decoration-none text-success">
                                                    <x-heroicon-o-hand-thumb-up class="icon me-1"/> 0
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="" class="text-decoration-none text-danger">
                                                    <x-heroicon-o-hand-thumb-down class="icon me-1"/> 0
                                                </a>
                                            </li>
                                            <li class="list-inline-item float-end">
                                                <a href="" class="text-success text-decoration-none">
                                                    <x-heroicon-o-shield-check class="icon"/> behandeld
                                                </a>

                                                <span class="text-muted mx-1">|</span>

                                                <a href="" class="text-decoration-none text-danger">
                                                    <x-heroicon-o-trash class="icon"/>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="list-group-item d-flex">
                                    <div class="flex-shrink-0"><img class="rounded-circle shadow-sm" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                    <div class="ms-3">
                                        <div class="fw-bold">Commenter Name <span class=" ms-1 badge border-0 badge-gray">kernlid - ontwikkeling</span></div>
                                        <span>Momenteel ervaren een doelgerichte spam actie tegen de commentaren van dit artikel. We werken aan een oplossing en houden jullie op de hoogte.</span>

                                        <ul class="list-inline pt-2">
                                            <li class="list-inline-item">
                                                <a href="" class="text-decoration-none text-success">
                                                    <x-heroicon-o-hand-thumb-up class="icon me-1"/> 0
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="" class="text-decoration-none text-danger">
                                                    <x-heroicon-o-hand-thumb-down class="icon me-1"/> 0
                                                </a>
                                            </li>
                                            <li class="list-inline-item float-end">
                                                <a href="" class="text-danger text-decoration-none">
                                                    <x-heroicon-o-shield-exclamation class="icon"/> rapporteren
                                                </a>

                                                <span class="text-muted mx-1">|</span>

                                                <a href="" class="text-danger text-decoration-none">
                                                    <x-heroicon-o-trash class="icon"/>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </section>

                    {{-- Reaction form --}}
                    <section class="mb-5 border-top border-green pt-3">
                        <form class="card bg-white border-0 shadow-sm">
                            <div class="card-body">
                                <textarea class="form-control" rows="3" placeholder="Join the discussion and leave a comment!"></textarea>
                            </div>

                            <div class="card-footer border-top-0 bg-light">
                                <button type="submit" class="btn btn-submit btn-sm">
                                    <x-heroicon-s-chat-bubble-left-right class="icon me-1"/> Reageren
                                </button>

                                <button type="reset" class="btn btn-sm btn-link">
                                    Reset
                                </button>
                            </div>
                        </form>
                    </section>
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
@endsection
