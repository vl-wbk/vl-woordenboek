<x-public-profile :user="$user">
    <x-slot name="action">
        <button class="btn minimal-btn-primary">
            nieuwe suggestie
        </button>
    </x-slot>

    <div class="card border-0 shadow-sm minimal-card">
        @if (auth()->user()->is($user))
            <div class="card-header bg-white border-bottom px-4 py-3">
                <div class="d-flex flex-column flex-md-row gap-3">
                    <div class="search-wrapper w-100">
                        <input type="text" class="form-control minimal-input" placeholder="Search items...">
                    </div>

                    <div class="filter-actions d-flex gap-2 flex-shrink-0">
                        <select class="form-select minimal-select w-auto">
                            <option value="">All statussen</option>
                            <option value="1">Design</option>
                            <option value="2">Development</option>
                        </select>

                        <button class="btn minimal-btn">
                            Zoeken
                        </button>
                    </div>
                </div>
            </div>
        @endif

      <!-- Card Body: The Listing -->
      <div class="card-body p-0">
            <div class="minimal-list">
          <!-- List Item 1 -->
          <!--
/**
 * Component: Minimal List Item with Actions
 * Description: A list item row featuring the title, metadata, a status badge, and a clean dropdown menu for actions.
 */
-->
    @foreach ($results as $article)
    <div class="minimal-list-item px-4 py-3 d-flex justify-content-between align-items-center">
  <div>
    <h5 class="item-title color-green">{{ $article->word }}</h5>

    <div class="me-5">
        <p class="item-meta text-dark my-2">Updated 2 days ago &middot;</p>

    <ul class="list-inline item-meta mb-0">
        <li class="list-inline-item">
            <strong>Created:</strong> {{ $article->created_at->format('M d, Y') }} <small class="ms-1 fst-italic">(laatste wijziging: DD/MM/YYYY)</small>
        </li>
    </ul>
    </div>
  </div>

  <!-- Right side wrapper for badge and actions -->
  <div class="item-actions d-flex align-items-center gap-3">
    <span class="badge minimal-badge">Active</span>

    <!-- Actions Dropdown -->
    <div class="dropdown">
      <button class="btn minimal-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Acties">
        <!-- Heroicon: ellipsis-vertical -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
        </svg>
      </button>
      <ul class="dropdown-menu dropdown-menu-end minimal-dropdown">
        <li><a class="dropdown-item d-flex align-items-center gap-2" href="#">Bewerken</a></li>
        <li><a class="dropdown-item d-flex align-items-center gap-2" href="#">Details bekijken</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger d-flex align-items-center gap-2" href="#">Verwijderen</a></li>
      </ul>
    </div>
  </div>
</div>

    @endforeach

        </div>
      </div>

      <!-- Card Footer: Pagination -->
      <div class="card-footer bg-white border-top px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
        <span class="text-muted small mb-3 mb-md-0">
            Toont <span class="fw-semibold text-dark">{{ $results->firstItem() }} - {{ $results->lastItem() }}</span> van <span class="fw-semibold text-dark">{{ $results->total() }}</span> suggesties
        </span>
        @if ($results->hasPages())
        <nav aria-label="Page navigation">
          <ul class="pagination minimal-pagination mb-0">
            <li class="page-item disabled">
              <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav>
        @endif
      </div>
    </div>

  </div>

    @if ($results->total() > 0)

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
