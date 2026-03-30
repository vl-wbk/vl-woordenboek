<x-public-profile :user="$user">
    @if ($results->total() > 0)
        <div class="card-shadcn overflow-hidden mb-3">
            <div class="list-group list-group-flush">
                @foreach ($results as $article)
                    <div class="list-group-item p-3 word-item position-relative">
                        @if ($article->state->is(\App\Enums\ArticleStates::Archived))
                            <a href="{{ route('word-information.show', $article) }}" class="stretched-link"></a>
                        @endif

                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="fw-bold mb-0 d-inline-block me-2">{{ $article->word }}</h5>
                            </div>
                        </div>

                        <div class="text-muted mb-3">
                            {!! str($article->description)->words(30)->markdown()->sanitizeHtml() !!}
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3 text-muted-foreground small">
                                {{-- Submission Date --}}
                                <div class="d-flex align-items-center">
                                    <x-heroicon-o-calendar class="icon-xs me-1 opacity-70" style="width: 14px;"/>
                                    <span>Ingediend op {{ $article->created_at->translatedFormat('d F Y') }}</span>
                                </div>

                                @if ($article->editor()->exists())
                                    <div class="border-start" style="height: 12px; opacity: 0.3;"></div>

                                    {{-- Downvotes --}}
                                    <div class="d-flex align-items-center transition-colors hover:text-danger">
                                        <x-heroicon-o-hand-thumb-down class="icon-xs me-1" style="width: 14px;"/>
                                        <span>Toegewezen redacteur: {{ $article->editor->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <hr>

        <div class="d-flex align-items-center justify-content-between mt-3">
            <div class="text-muted small">
                Toont <span class="fw-semibold text-dark">{{ $results->firstItem() }}-{{ $results->lastItem() }}</span> van <span class="fw-semibold text-dark">{{ $results->total() }}</span> suggesties
            </div>

            @if ($results->hasPages())
                {{ $results->links() }}
            @endif
        </div>
    @else {{-- Show the blankslate --}}
        <div class="card-shadcn border-dashed py-6 my-4 bg-light bg-opacity-10">
            <div class="d-flex flex-column align-items-center justify-content-center text-center p-5">

                <div class="mb-4 position-relative">
                    <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border: 1px solid var(--border);">
                        <x-heroicon-o-book-open class="text-muted-foreground" style="width: 32px;"/>
                    </div>
                </div>

                <h4 class="fw-bold mb-2">{{ __('pages/suggestions/index.no-results.heading') }}</h4>
                <p class="text-muted small mx-auto mb-4" style="max-width: 800px;">
                    {{ __('pages/suggestions/index.no-results.first-sentence') }}<br>
                    {{ __('pages/suggestions/index.no-results.second-sentence') }}
                </p>

                <div class="d-flex gap-2">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-shadcn btn-dark-shadcn px-4">
                            Account maken
                        </a>
                    @endauth

                    <a href="{{ route('definitions.create') }}" class="btn btn-shadcn shadow-sm btn-dark-shadcn px-4">
                        <x-heroicon-o-plus class="icon me-1" style="width: 18px;"/> Suggestie indienen
                    </a>
                </div>
            </div>
        </div>
    @endif
</x-public-profile>
