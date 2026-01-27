@extends('layouts.application-blank', ['title' => __('pages/suggestions/index.page-title')])

@section('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class=" py-4 py-md-5">
                <div class="row">
                    <h1 class="display-6 fw-bold">{!! __('pages/suggestions/index.jumbotron.heading', ['applicationName' => config('app.name', 'Laravel')]) !!}</h1>
                    <p class="col-12 fs-5 pb-3 border-bottom">
                        {{ __('pages/suggestions/index.jumbotron.text.first-sentence', ['count' => $suggestionCount]) }} <br>
                        {{ __('pages/suggestions/index.jumbotron.text.second-sentence') }}
                    </p>

                    <form class="col-12 col-md-10 col-lg-7 mt-3 mt-md-4" action="{{ route('suggestions:index') }}" method="GET">
                        <div class="row g-2 g-sm-3">
                            <div class="col-12 col-sm-8 col-lg-10">
                                <input
                                    type="text"
                                    class="form-control bg-white shadow-sm"
                                    name="zoekterm"
                                    value="{{ request()->get('zoekterm') }}"
                                    placeholder="{{ __('pages/suggestions/index.jumbotron.form.search-placeholder') }}"
                                    aria-label="searchterm"
                                >
                            </div>
                            <div class="col-12 col-sm-4 col-lg-2 d-grid">
                                <button type="submit" class="btn shadow-sm w-100 btn-submit">
                                    <x-heroicon-o-magnifying-glass class="icon me-1"/> {{ __('pages/suggestions/index.jumbotron.form.buttons.submit') }}
                                </button>
                            </div>
                            <div class="col-12 d-flex flex-wrap gap-2">
                                <x-sortable-reset>
                                    {{ __('pages/suggestions/index.jumbotron.form.buttons.reset') }}
                                </x-sortable-reset>

                                @if (request()->has('zoekterm'))
                                    <a href="{{ route('suggestions:index') }}" class="btn btn-sm btn-outline-danger">
                                        <x-tabler-x class="icon me-1"/> {{ __('pages/suggestions/index.jumbotron.form.current-search-term') }}:
                                        <strong>{{ request()->get('zoekterm') }}</strong>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3 pt-md-4">
        <div class="row gy-4 pb-2">
            <!-- Sidebar -->
            <div class="col-12 col-lg-3 order-2 order-lg-1">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <a href="{{ route('definitions.create') }}" class="btn btn-submit w-100 shadow-sm">
                            <x-heroicon-o-plus class="icon me-1"/> {{ __('pages/search.sidenav.buttons.submit-suggestion') }}
                        </a>
                    </div>
                    <div class="card-body bg-white">
                        <h5 class="mb-3 card-title color-green">
                            <x-heroicon-s-funnel class="icon me-1"/> {{ __('pages/suggestions/index.sidenav.headings.submitted-since') }}
                        </h5>

                        <x-filter-link field="created_after" value="{{ now()->subWeek()->format('Y-m-d') }}">
                            {{ __('pages/search.sidenav.filters.last-week') }}
                        </x-filter-link>
                        <x-filter-link field="created_after" value="{{ now()->subMonth()->format('Y-m-d') }}">
                            {{ __('pages/search.sidenav.filters.last-month') }}
                        </x-filter-link>
                        <x-filter-link field="created_after" value="{{ now()->subYear()->format('Y-m-d') }}">
                            {{ __('pages/search.sidenav.filters.last-year') }}
                        </x-filter-link>

                        <hr>

                        <h5 class="mb-3 card-title color-green">
                            <x-heroicon-s-funnel class="icon me-1"/> {{ __('pages/suggestions/index.sidenav.state-filters.heading') }}
                        </h5>

                        <x-filter-link field="status" value="{{ \App\Enums\ArticleStates::New }}">
                            {{ __('pages/suggestions/index.sidenav.state-filters.sggestion') }}
                        </x-filter-link>
                        <x-filter-link field="status" value="{{ \App\Enums\ArticleStates::Draft }}">
                            {{ __('pages/suggestions/index.sidenav.state-filters.draft') }}
                        </x-filter-link>
                        <x-filter-link field="status" value="{{ \App\Enums\ArticleStates::Approval }}">
                            {{ __('pages/suggestions/index.sidenav.state-filters.under-review') }}
                        </x-filter-link>
                        <x-filter-link field="status" value="{{ \App\Enums\ArticleStates::Published }}">
                            {{ __('pages/suggestions/index.sidenav.state-filters.publication') }}
                        </x-filter-link>

                        <hr>

                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <x-heroicon-o-pencil-square class="icon color-green me-2"/>

                                    <a href="{{ route('suggestions:index') }}" class="text-decoration-none text-muted">
                                        {{ __('pages/search.sidenav.my-suggestions') }}
                                    </a>
                                </div>

                                @auth
                                    <span class="badge text-bg-danger rounded-pill">
                                        {{ $suggestionCount }}
                                    </span>
                                @endauth
                            </li>

                            <li class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <x-heroicon-o-bookmark class="icon color-green me-2"/>

                                    <a href="{{ route('bookmarks:index') }}" class="text-decoration-none text-muted">
                                        {{ __('pages/search.sidenav.my-saved-words') }}
                                    </a>
                                </div>

                                @auth
                                    <span class="badge text-bg-danger rounded-pill">
                                        {{ auth()->user()->bookmarks->count() }}
                                    </span>
                                @endauth
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-12 col-lg-9 order-1 order-lg-2">
                @if ($results->total() > 0)
                    <div class="card bg-white border-0 border-bottom shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="ps-2">#</th>
                                            <th scope="col" class="text-muted">{{ __('pages/suggestions/index.table.columns.status') }}</th>
                                            <th scope="col" class="text-muted">{{ __('pages/suggestions/index.table.columns.editor') }}</th>
                                            <th scope="col" class="text-muted">
                                                <x-sortable-header field="word" :current-sort="request('sort')">
                                                    {{ __('pages/suggestions/index.table.columns.lemma') }}
                                                </x-sortable-header>
                                            </th>
                                            <th scope="col" class="text-muted">
                                                <x-sortable-header field="edited" :current-sort="request('sort')">
                                                    {{ __('pages/suggestions/index.table.columns.last-edited') }}
                                                </x-sortable-header>
                                            </th>
                                            <th scope="col" class="text-muted" colspan="2">
                                                <x-sortable-header field="created" :current-sort="request('sort')">
                                                    {{ __('pages/suggestions/index.table.columns.submitted_at') }}
                                                </x-sortable-header>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results as $result)
                                            <tr>
                                                <th scope="row" class="color-green ps-2">#{{ $result->id }}</th>
                                                <td>
                                                    <span class="badge badge-{{ $result->state->getColor() }}">
                                                        {{ $result->state->getLabel() }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($result->editor()->exists())
                                                        {{ $result->editor->name }}
                                                    @else
                                                        <span class="color-green fst-italic">- geen</span>
                                                    @endif
                                                </td>

                                                <td class="text-break">{{ $result->word }}</td>
                                                <td>{{ $result->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    @if ($result->updated_at->eq($result->created_at))
                                                        <span class="color-green">-</span>
                                                    @else
                                                        {{ $result->created_at->format('d/m/Y H:i:s') }}
                                                    @endif
                                                </td>

                                                <td class="text-end">
                                                    @if ($result->isPublished())
                                                        <a href="{{ route('word-information.show', $result) }}" class="text-muted text-decoration-none">
                                                            <x-heroicon-o-eye class="icon me-1"/> {{ __('pages/suggestions/index.table.actions.view') }}
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <x-definitions.pagination :results="$results" />
                @else {{-- No results state --}}
                    <div class="card bg-sidenav text-center shadow-sm border-0">
                        <div class="card-body p-4">
                            <x-tabler-inbox class="icon-blankslate color-green icon pb-3"/>
                            <h5 class="card-title fw-bold">{{ __('pages/suggestions/index.no-results.heading') }}</h5>

                            <p class="card-text text-muted">
                                {{ __('pages/suggestions/index.no-results.first-sentence') }}<br>
                                {{ __('pages/suggestions/index.no-results.second-sentence') }}
                            </p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
