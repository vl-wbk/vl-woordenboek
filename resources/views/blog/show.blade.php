@extends('layouts.application-blank', ['title' => trans('Nieuwsartikelen')])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="border-green mb-3 border-bottom">
                    <header class="mb-4">
                        <h1 class="fw-bolder text-gold mb-1">{{ $post->title }}</h1>
                        <div class="text-muted fst-italic mb-2">{{ __('Gepubliceerd door :author op :date', ['author' => $post->author->name, 'date' => $post->created_at->locale('nl_BE')->isoFormat('DD MMMM YYYY HH:mm') ]) }}</div>

                        <!-- Post categories-->
                        @if ($post->category()->exists())
                            @foreach ($post->category as $category)
                                <a href="{{ route('categories:show', $category) }}" class="badge badge-warning shadow-sm text-decoration-none" href="#!">
                                    <x-heroicon-s-tag class="icon icon-sm me-1"/> Ongecategoriseerd
                                </a>
                            @endforeach
                        @else  {{-- No ctageories are found so simply return the uncategorised label. --}}
                            <span class="badge badge-warning shadow-sm text-decoration-none" href="#!">
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
                <section class="mb-5">
                    <div class="card bg-light border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center border-bottom-0 fw-bold color-green bg-sidenav">
                            <span>Reacties (0)</span>

    <nav aria-label="Page navigation example">
      <ul class="pagination mb-0"> <li class="page-item disabled">
          <a class="page-link" href="#" tabindex="-1">Previous</a>
        </li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
          <a class="page-link" href="#">Next</a>
        </li>
      </ul>
    </nav>
  </div>
                            <div class="card-body">
                                <!-- Comment with nested comments-->
                                <div class="d-flex mb-4">
                                    <!-- Parent comment-->
                                    <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                    <div class="ms-3">
                                        <div class="fw-bold">Commenter Name</div>
                                        If you're going to lead a space frontier, it has to be government; it'll never be private enterprise. Because the space frontier is dangerous, and it's expensive, and it has unquantified risks.
                                    </div>
                                </div>
                                <!-- Comment with nested comments-->
                                <div class="d-flex mb-4">
                                    <!-- Parent comment-->
                                    <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                    <div class="ms-3">
                                        <div class="fw-bold">Commenter Name</div>
                                        If you're going to lead a space frontier, it has to be government; it'll never be private enterprise. Because the space frontier is dangerous, and it's expensive, and it has unquantified risks.
                                    </div>
                                </div>
                                <!-- Comment with nested comments-->
                                <div class="d-flex mb-4">
                                    <!-- Parent comment-->
                                    <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                    <div class="ms-3">
                                        <div class="fw-bold">Commenter Name</div>
                                        If you're going to lead a space frontier, it has to be government; it'll never be private enterprise. Because the space frontier is dangerous, and it's expensive, and it has unquantified risks.
                                    </div>
                                </div>
                                <!-- Comment with nested comments-->
                                <div class="d-flex mb-4">
                                    <!-- Parent comment-->
                                    <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                    <div class="ms-3">
                                        <div class="fw-bold">Commenter Name</div>
                                        If you're going to lead a space frontier, it has to be government; it'll never be private enterprise. Because the space frontier is dangerous, and it's expensive, and it has unquantified risks.
                                    </div>
                                </div>
                                <!-- Single comment-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                    <div class="ms-3">
                                        <div class="fw-bold">Commenter Name</div>
                                        When I look at the universe and all the ways the universe wants to kill us, I find it hard to reconcile that with statements of beneficence.
                                    </div>
                                </div>

                                 <!-- Comment form-->
                                <form class="mt-4 border-top">
                                    <textarea class="form-control mt-2" rows="3" placeholder="Join the discussion and leave a comment!"></textarea>
                                    <button type="submit" class="btn btn-sm btn-submit mt-3">ddd</button>
                                </form>
                            </div>
                        </div>
                    </section>
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
