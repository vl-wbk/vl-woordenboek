<x-public-profile :user="$user">
    @if ($contributions->total() > 0)
        <div class="card-shadcn overflow-hidden mb-3">
            <div class="list-group list-group-flush">
                @foreach ($contributions as $article)
                    <div class="list-group-item p-3 word-item position-relative">
                        <a href="{{ route('word-information.show', $article) }}" class="stretched-link"></a>

                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="fw-bold mb-0 d-inline-block me-2">{{ $article->word }}</h5>
                            </div>
                            <a href="{{ route('bookmark:remove', $article) }}" class="text-danger text-decoration-none position-relative" style="z-index: 2;">
                                <x-heroicon-o-bookmark-slash class="icon me-1"/> Vergeten
                            </a>
                        </div>

                        <div class="text-muted mb-3">
                            {!! str($article->description)->words(30)->markdown()->sanitizeHtml() !!}
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            @foreach ($article->labels as $label)
                                <a href="{{ route('label:show', $label) }}" class="badge-chip position-relative" style="z-index: 2;"">
                                    <x-heroicon-o-tag class="icon-xs me-1"/>
                                    {{ $label->name }}
                                </a>
                            @endforeach

                            <div class="ms-auto">
                                <span class="text-muted small me-2"><x-heroicon-o-eye class="icon me-1"/> {{ $article->views }}</span>
                                <span class="text-muted small me-2"><x-heroicon-o-hand-thumb-up class="icon me-1"/> {{ $article->upvoters()->count() }}</span>
                                <span class="text-muted small me-2"><x-heroicon-o-hand-thumb-down class="icon me-1"/> {{ $article->downvoters()->count() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <hr>

        <div class="d-flex align-items-center justify-content-between mt-3">
            <div class="text-muted small">
                Toont <span class="fw-semibold text-dark">{{ $contributions->firstItem() }}-{{ $contributions->lastItem() }}</span> van <span class="fw-semibold text-dark">{{ $contributions->total() }}</span> resultaten
            </div>

            @if ($contributions->hasPages())
                {{ $contributions->links() }}
            @endif
    @else {{-- Show the blankslate --}}
        <div class="card-shadcn border-dashed py-6 my-4 bg-light bg-opacity-10">
            <div class="d-flex flex-column align-items-center justify-content-center text-center p-5">

                <div class="mb-4 position-relative">
                    <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border: 1px solid var(--border);">
                        <x-heroicon-o-book-open class="text-muted-foreground" style="width: 32px;"/>
                    </div>
                </div>

                <h4 class="fw-bold mb-2">{{ $user->name }} heeft nog geen publicaties</h4>
                <p class="text-muted small mx-auto mb-4" style="max-width: 800px;">
                    Deze collectie is momenteel nog leeg. Zodra {{ $user->name }} nieuwe Vlaamse termen of uitdrukkingen publiceert, verschijnen ze hier.
                </p>

                <div class="d-flex gap-2">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-shadcn btn-dark-shadcn px-4">
                            Account maken
                        </a>
                    @endauth

                    <a href="{{ route('word-information.show', $randomArticle) }}" class="btn shadow-sm btn-shadcn btn-outline-shadcn px-4 bg-white">
                        Ontdek andere woorden
                    </a>
                </div>
            </div>
        </div>
    @endif
</x-public-profile>
