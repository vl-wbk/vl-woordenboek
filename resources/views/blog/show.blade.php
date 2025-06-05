@extends('layouts.application-blank', ['title' => trans('Nieuwsartikelen')])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="border-green mb-3 @if ($post->comments_enabled && $post->comments->count() > 0) border-bottom @endif">
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
                @if ($post->comments_enabled && $post->comments->count() > 0)
                    <section class="pb-3">
                        <div class="card bg-light border-0 shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center fw-bold">
                                <span class="color-green" id="reacties">Reacties ({{ $post->comments->count() }})</span>

                                {{ $comments->links('blog.components.pagination') }}
                            </div>

                            <ul class="list-group list-group-flush">
                                @foreach ($comments as $comment)
                                    <livewire:articleComments.post-comment :comment=$comment />
                                @endforeach
                            </ul>
                        </div>
                    </section>
                @endif

                {{-- Reaction form --}}
                @if (optional(auth()->user())->can('canComment', $post))
                    <section class="mb-5 border-top border-green pt-3">
                        <form method="POST" action="{{ route('comment:create', $post) }}" class="card bg-white border-0 shadow-sm">
                            @csrf {{-- Cross-Site Request Forgery protection --}}

                            <div class="card-body">
                                <textarea class="form-control @error('reactie') is-invalid @enderror" name="reactie" rows="3" placeholder="Sluit je aan bij de discussie doormiddel van een reactie achter te laten."></textarea>
                                <x-forms.validation-error field="reactie"/>
                            </div>

                            <div class="card-footer border-top-0 bg-light">
                                <button type="submit" class="btn btn-submit btn-sm">
                                    <x-heroicon-s-chat-bubble-left-right class="icon me-1"/> Reageren
                                </button>

                                <button type="reset" class="btn btn-sm btn-link">
                                    Reset
                                </button>

                                <a href="" _target="blank" class="text-muted text-decoration-none fw-semibold float-end">
                                    <x-heroicon-s-book-open class="icon color-green me-1"/> Moderatie FAQ
                                </a>
                            </div>
                        </form>
                    </section>
                @endif
            </div> {{-- END comments section --}}


                <!-- Side widgets-->
                <div class="col-lg-4">
                    <!-- Search widget-->
                    <h5 class="border-bottom pb-2 border-green color-green fw-bold">
                        <x-heroicon-o-magnifying-glass-circle class="icon me-1"/> Artikel opzoeken
                    </h4>

                    <form action="{{ route('news:index') }}" method="GET" class="mb-4 border-0 shadow-sm">
                        <div class="input-group">
                            <input class="form-control" type="text" name="zoekterm" placeholder="Zoek op de titel van het artikel..." aria-label="Zoek op de titel van het artikel..." value="{{ request()->get('zoekterm') }}" aria-describedby="button-search" />
                            <button class="btn btn-submit" id="button-search" type="submit">
                                <x-heroicon-s-magnifying-glass class="icon me-1"/> Zoek
                            </button>
                        </div>
                    </form>

                    <!-- Categories widget-->
                    <h5 class="border-bottom pb-2 border-green color-green fw-bold">
                        <x-heroicon-s-tag class="icon me-1"/> Categorieen
                    </h4>

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
