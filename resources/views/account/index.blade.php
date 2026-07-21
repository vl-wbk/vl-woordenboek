<x-public-profile :user="$user">
    <style>
    /**
     * Component Styles: Contributions List & Empty State
     */

    /* List Item Formatting overrides */
    .minimal-list-item {
    border-bottom: 1px solid #E5E7EB;
    transition: background-color 0.2s ease;
    }

    .minimal-list-item:hover {
    /** background-color: #F9FAFB; */
    }

    .minimal-list-item:last-child {
    border-bottom: none;
    }

    /* Ensure markdown paragraphs don't break the tight layout */
    .minimal-list-item .item-meta p {
    margin-bottom: 0;
    line-height: 1.5;
    }

    /* Icons */
    .icon-xs {
    width: 0.85rem;
    height: 0.85rem;
    vertical-align: -0.125em;
    }

    .icon-sm {
    width: 1rem;
    height: 1rem;
    vertical-align: -0.125em;
    }

    /* Badge hover state inside the row */
    .minimal-badge {
    background-color: #F3F4F6;
    border: 1px solid transparent;
    color: #4B5563;
    font-weight: 500;
    padding: 0.35em 0.65em;
    border-radius: 6px;
    transition: all 0.2s ease;
    }

    .minimal-badge:hover {
    background-color: #E5E7EB;
    color: #111827;
    }

    /* Empty State Styling */
    .minimal-empty-state {
    border: 1px dashed #000; /* Dashed border for empty states */
    border-radius: 8px;
    background-color: #fff;
    }

    .empty-state-icon-wrapper {
    width: 64px;
    height: 64px;
    background-color: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .empty-state-icon {
    width: 28px;
    height: 28px;
    color: #9CA3AF;
    }

    /* Fix Laravel Pagination alignment to fit minimal style */
    .minimal-pagination-wrapper .pagination {
    margin-bottom: 0;
    }
</style>

<!--
/**
 * Component: Minimal User Contributions List (with Search Button)
 * Description: A clean, card-based listing of dictionary publications featuring an explicit search input and submit button alongside the minimal layout.
 */
-->
<div class="contributions-section">
    <!-- Search Bar with Submit Button -->
    @if (auth()->user()->is($user))
        <div class="mb-4">
        <form method="GET" action="{{ request()->url() }}">
            <div class="d-flex gap-2">
                <div class="position-relative flex-grow-1">
                    <!-- Optional: You can place a search icon inside here if desired -->
                    <input type="text" name="zoekterm" value="{{ request()->input('zoekterm') }}" class="form-control bg-white minimal-input w-100" placeholder="Zoeken in publicaties van {{ $user->name }}..." autocomplete="off">
                </div>
                <!-- The new explicit submit button -->
                <button type="submit" class="btn minimal-btn text-white bg-dark px-4 flex-shrink-0">
                    Zoeken
                </button>
            </div>
        </form>
    </div>
    @endif

    @if ($contributions->total() > 0)
        <!-- Listing Card -->
        <div class="card border-0 shadow-sm minimal-card mb-4">
            <div class="card-body p-0 minimal-list">
                @foreach ($contributions as $article)
                    <div class="minimal-list-item px-4 py-3 position-relative">
                        <!-- Stretched link for entire row clickability -->
                        <a href="{{ route('word-information.show', $article) }}" class="stretched-link"></a>

                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h5 class="item-title color-green fw-bold mb-0">{{ $article->word }}</h5>
                        </div>

                        <div class="item-meta text-muted mb-3 pe-4">
                            {!! str($article->description)->words(30)->markdown()->sanitizeHtml() !!}
                        </div>

                        <!-- Footer: Labels and Stats -->
                        <div class="d-flex align-items-center justify-content-between position-relative" style="z-index: 2;">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($article->labels as $label)
                                    <a href="{{ route('label:show', $label) }}" class="badge minimal-badge text-decoration-none">
                                        <x-heroicon-o-tag class="icon-xs me-1"/>
                                        {{ $label->name }}
                                    </a>
                                @endforeach
                            </div>

                            <div class="d-flex align-items-center gap-3 text-muted small">
                                <span><x-heroicon-o-eye class="icon-sm me-1"/> {{ $article->views }}</span>
                                <span><x-heroicon-o-hand-thumb-up class="icon-sm text-success me-1"/> {{ $article->upvoters()->count() }}</span>
                                <span><x-heroicon-o-hand-thumb-down class="icon-sm text-danger me-1"/> {{ $article->downvoters()->count() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="d-flex border-top pt-3 flex-column flex-md-row align-items-center justify-content-between mt-3">
            <div class="text-muted small mb-3 mb-md-0">
                Toont <span class="fw-semibold text-dark">{{ $contributions->firstItem() }}-{{ $contributions->lastItem() }}</span> van <span class="fw-semibold text-dark">{{ $contributions->total() }}</span> resultaten
            </div>

            @if ($contributions->hasPages())
                <div class="minimal-pagination-wrapper">
                    {{ $contributions->links() }}
                </div>
            @endif
        </div>

    @else 
        <!-- Blank Slate / Empty State -->
        <div class="minimal-empty-state text-center px-4 py-5 mb-4">
            <div class="mb-4 shadow-sm d-inline-flex align-items-center justify-content-center empty-state-icon-wrapper">
                <x-heroicon-o-book-open class="empty-state-icon"/>
            </div>

            <h4 class="fw-bold text-dark mb-2">{{ $user->name }} heeft nog geen publicaties</h4>
            <p class="text-muted small mx-auto mb-4" style="max-width: 500px;">
                Deze collectie is momenteel nog leeg. Zodra {{ $user->name }} nieuwe Vlaamse termen of uitdrukkingen publiceert, verschijnen ze hier.
            </p>

            <div class="d-flex justify-content-center gap-3">
                @guest
                    <a href="{{ route('register') }}" class="btn minimal-btn-primary px-4">
                        Account maken
                    </a>
                @endauth

                <a href="{{ route('word-information.show', $randomArticle) }}" class="btn shadow-sm minimal-btn px-4">
                    Ontdek andere woorden
                </a>
            </div>
        </div>
    @endif
</div>
</x-public-profile>
