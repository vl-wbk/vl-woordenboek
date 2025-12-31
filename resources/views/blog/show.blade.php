@extends('layouts.application-blank', ['title' => trans('Nieuwsberichten')])

@section('content')
    <div class="mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <header class="mb-4 border-bottom pb-3">
                        <h1 class="fw-bolder text-gold mb-1">{{ $post->title }}</h1>
                        <div class="text-muted fst-italic mb-2">
                            {{ __('Gepubliceerd door :author op :date', ['author' => $post->author->name, 'date' => $post->created_at->locale('nl_BE')->isoFormat('DD MMMM YYYY HH:mm')]) }}
                        </div>

                        <!-- Post categories-->
                        @if ($post->category()->exists())
                            @foreach ($post->category as $category)
                                {{-- Loop through the ctageories that are associated with the article --}}
                                <a href="{{ route('categories:show', $category) }}"
                                   class="badge badge-primary text-decoration-none shadow-sm" href="#!">
                                    <x-heroicon-s-tag class="icon icon-sm me-1" /> {{ $category->name }}
                                </a>
                            @endforeach
                        @else
                            {{-- No ctageories are found so simply return the uncategorised label. --}}
                            <span class="badge badge-primary text-decoration-none shadow-sm">
                                <x-heroicon-s-tag class="icon icon-sm me-1" /> Ongecategoriseerd
                            </span>
                        @endif
                    </header>
                </div>
                <div class="col-lg-9">
                    <article class="border-green @if ($post->comments_enabled && $post->comments->count() > 0) border-bottom @endif mb-3">
                        <!-- Post content-->
                        <section class="article-section mb-4">
                            {!! str($post->content)->markdown()->sanitizeHtml() !!}
                        </section>
                    </article>

                    <!-- Comments section-->
                    @if ($post->comments_enabled && $post->comments->count() > 0)
                        <section class="pb-3">
                            <div class="card bg-light border-0 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center fw-bold bg-white">
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
                        <section class="border-top border-green mb-5 pt-3">
                            <form method="POST" action="{{ route('comment:create', $post) }}"
                                  class="card border-0 bg-white shadow-sm">
                                @csrf {{-- Cross-Site Request Forgery protection --}}

                                <div class="card-body">
                                <textarea class="form-control @error('reactie') is-invalid @enderror" name="reactie" rows="3"
                                          placeholder="Sluit je aan bij de discussie door middel van een reactie achter te laten."></textarea>
                                    <x-forms.validation-error field="reactie" />
                                </div>

                                <div class="card-footer border-top-0 bg-light">
                                    <button type="submit" class="btn btn-submit btn-sm">
                                        <x-heroicon-s-chat-bubble-left-right class="icon me-1" /> Reageren
                                    </button>

                                    <button type="reset" class="btn btn-sm btn-link">
                                        Resetten
                                    </button>

                                    <a href="" _target="blank"
                                       class="text-muted text-decoration-none fw-semibold float-end">
                                        <x-heroicon-s-book-open class="icon color-green me-1" /> Moderatie-FAQ
                                    </a>
                                </div>
                            </form>
                        </section>
                    @endif
                </div> {{-- END comments section --}}

                <!-- Side widgets-->
                <div class="col-lg-3">
                    <!-- Search widget-->
                    <h5 class="border-bottom border-green color-green fw-bold pb-2">
                        <x-heroicon-o-magnifying-glass-circle class="icon me-1" /> Nieuwsbericht opzoeken
                        </h4>

                        <form action="{{ route('news:index') }}" method="GET" class="mb-4 border-0 shadow-sm">
                            <div class="input-group">
                                <input class="form-control" type="text" name="zoekterm"
                                       placeholder="Zoek op de titel van het nieuwsbericht..."
                                       aria-label="Zoek op de titel van het nieuwsbericht..."
                                       value="{{ request()->get('zoekterm') }}" aria-describedby="button-search" />
                                <button class="btn btn-submit" id="button-search" type="submit">
                                    <x-heroicon-s-magnifying-glass class="icon me-1" /> Zoek
                                </button>
                            </div>
                        </form>

                        <!-- Categories widget-->
                        <h5 class="border-bottom border-green color-green fw-bold pb-2">
                            <x-heroicon-s-tag class="icon me-1" /> Categorieen
                            </h4>

                            <div class="border-bottom border-green pb-2">
                                @foreach ($categories as $category)
                                    <a href="{{ route('categories:show', $category) }}"
                                       class="badge badge-primary text-decoration-none shadow-sm">
                                        {{ $category->name }} <span
                                            class="fst-italic fw-bold">({{ $category->posts->count() }})</span>
                                    </a>
                                @endforeach
                            </div>

                            <a href="{{ url('feed') }}" class="btn w-100 btn-rss mt-2 text-white shadow-sm">
                                <x-heroicon-s-rss class="icon me-1" /> RSS-Feed
                            </a>

                </div>
            </div>
    </div>
@endsection
