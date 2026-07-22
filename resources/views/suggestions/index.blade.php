<x-public-profile :user="$user">
    <x-slot name="action">
        <a href="{{ route('definitions.create') }}" class="btn minimal-btn-primary">
            <x-tabler-file-plus class="icon me-1"/> nieuwe suggestie
        </a>
    </x-slot>

    @if ($suggestionCount > 0)
        <div class="card border-0 shadow-sm minimal-card">
            @if (auth()->user()->is($user))
                <form method="GET" class="card-header bg-white border-bottom px-4 py-3">
                    <div class="d-flex flex-column flex-md-row gap-3">
                        <div class="search-wrapper w-100">
                            <input type="text" name="zoekterm" value="{{ request()->get('zoekterm') }}" class="form-control bg-white minimal-input" placeholder="Zoeken op titel van het artikel...">
                        </div>

                        <div class="filter-actions d-flex gap-2 flex-shrink-0">
                            <select name="status" class="form-select bg-white minimal-select w-auto">
                                <option value="">Alle statussen</option>
                                
                                @foreach ($suggestionStates as $state)
                                    <option value="{{ $state->value }}" @selected((string) request()->query('status') === (string) $state->value)>
                                        {{ $state->getLabel() }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="btn minimal-btn">
                                <x-tabler-filter-2-search class="icon color-green me-1"/> Zoeken
                            </button>
                        </div>
                    </div>
                </form>
            @endif

            <div class="card-body p-0">
                <div class="minimal-list">
                    @forelse ($results as $article)
                        <div class="minimal-list-item px-4 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('suggestions:show', $article) }}" class="text-decoration-none">
                                    <h5 class="item-title color-green">{{ $article->word }}</h5>
                                </a>

                                <div class="me-5">
                                    <p class="item-meta text-dark my-2">
                                        {{ $article->seo_description }}
                                    </p>

                                    <ul class="list-inline item-meta mb-0">
                                        <li class="list-inline-item">
                                            <strong>Ingestuurd op:</strong> {{ $article->created_at->translatedFormat('d F, Y') }} 
                                            
                                            @if ($article->created_at->notEqualTo($article->updated_at))
                                                <small class="ms-1 fst-italic">(laatste wijziging: {{ $article->updated_at->translatedFormat('d F, Y') }})</small>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="item-actions d-flex align-items-center gap-3">
                                <span class="badge minimal-badge shadow-sm">{{ $article->state->getLabel() }}</span>

                                @can ('view-suggestion', $article)
                                    <div class="dropdown">
                                        <a href="{{ route('suggestions:show', $article) }}" class="btn minimal-action-btn" type="button">
                                            <x-tabler-eye style="width: 1.25rem; height: 1.25rem;"/>
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="px-4 bg-white py-3">
                            <div class="alert alert-warning py-2 mb-0" role="alert">
                                <p>
                                    Het lijkt erop dat er geen suggesties zijn gevonden matchende met de criteria die je hebt opgegeven. <br>
                                    Via de onderstaande knop je de uitgevoerde zoekopdracht annuleren.
                                </p>
                                
                                <hr class="my-2">
                                
                                <p class="mb-0">
                                    <a href="{{ route('suggestions:index') }}" class="fw-bold text-decoration-none text-warning-emphasis">
                                        Zoekopdracht wissen
                                    </a>
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Card Footer: Pagination -->
            <div class="card-footer bg-white border-top px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <span class="text-muted small mb-3 mb-md-0">
                    Toont <span class="fw-semibold text-dark">{{ $results->firstItem() ?? '0' }} - {{ $results->lastItem() ?? '0' }}</span> van <span class="fw-semibold text-dark">{{ $results->total() }}</span> suggesties
                </span>
        
                @if ($results->hasPages())
                    {{ $results->links() }}
                @endif
            </div>
        @else {{-- Show the blankslate --}}
            <div class="card-shadcn bg border-dashed py-6 my-4 bg-white">
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
