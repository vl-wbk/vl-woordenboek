@extends('layouts.application-blank', ['title' => 'Label informatie'])

@section('jumbotron')
    <header class="bg-white border-bottom shadow-sm">
        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-10">
                    <div class="row align-items-end">
                        {{-- Identity --}}
                        <div class="col-lg-7">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('/') }}">
                                            <x-heroicon-o-home class="icon me-1" />Home
                                        </a>
                                    </li>

                                    <li class="breadcrumb-item">
                                        <a href="{{ route('label:index') }}">
                                            Labels
                                        </a>
                                    </li>

                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $label->name }}
                                    </li>
                                </ol>
                            </nav>

                            <h1 class="display-3 fw-black color-green mb-3">{{ $label->name }}</h1>

                            @if ($label->description)
                                <p class="lead text-secondary mb-0">{{ $label->description }}</p>
                            @else
                                <p class="text-muted fst-italic mb-0">Geen aanvullende beschrijving beschikbaar.</p>
                            @endif
                        </div>

                        {{-- Quick Stats --}}
                        <div class="col-lg-5 mt-4 mt-lg-0">
                            <div class="d-flex flex-column flex-md-row justify-content-lg-end gap-3">
                                <div class="card border-0 bg-light px-4 py-3 rounded-4 shadow-sm flex-fill flex-lg-grow-0">
                                    <span class="text-muted small text-uppercase fw-bold d-block mb-1">Totaal Woorden</span>
                                    <div class="d-flex align-items-center">
                                        <x-heroicon-s-book-open class="icon color-green me-2" style="width: 24px; height: 24px;"/>
                                        <span class="h4 mb-0 fw-bold">{{ $relatedArticles->total() }}</span>
                                    </div>
                                </div>
                                @if ($popularWord)
                                    <a href="{{ route('word-information.show', $popularWord) }}" class="card border-0 bg-danger-soft text-decoration-none px-4 py-3 rounded-4 shadow-sm transition-hover flex-fill flex-lg-grow-0">
                                        <span class="text-danger small text-uppercase fw-bold d-block mb-1">Meest Gezocht</span>
                                        <div class="d-flex align-items-center">
                                            <x-heroicon-s-fire class="icon text-danger me-2" style="width: 24px; height: 24px;"/>
                                            <span class="h4 mb-0 fw-bold text-dark">{{ $popularWord->word }}</span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <style>
        .smallest {
            font-size: 0.75rem;
        }

        .btn-xs {
            padding: 0.2rem 0.5rem;
            font-size: 0.65rem;
        }

        /* Line clamp for 2 lines */
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Ensure pagination isn't huge */
        .pagination-sm .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>
    <div class="py-5">
        <div class="container-fluid">
            {{-- Unified Col-10 wrapper for content alignment --}}
            <div class="row justify-content-center">
                <div class="col-10">
                    <div class="row g-4">

                        {{-- Left Column: Data Sidebar --}}
                        <aside class="col-12 col-lg-3">
                            <div class="sticky-top" style="top: 2rem;">
                                <h2 class="h4 text-gold fw-bold mb-3 d-flex align-items-center">
                                    <x-heroicon-o-chart-bar class="icon me-2"/>
                                    Label Statistieken
                                </h2>

                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-0">
                                        <div class="list-group list-group-flush rounded-4">
                                            @foreach([
                                                ['icon' => 'heroicon-s-eye', 'label' => 'Totaal Weergaves', 'value' => $analytics['views']['statistic']],
                                                ['icon' => 'heroicon-s-link', 'label' => 'Gekoppelde Woorden', 'value' => $analytics['word']['statistic']],
                                                ['icon' => 'heroicon-s-user-group', 'label' => 'Unieke Auteurs', 'value' => $analytics['contributor']['statistic']],
                                                ['icon' => 'heroicon-s-chat-bubble-left-ellipsis', 'label' => 'Actieve Meldingen', 'value' => $analytics['report']['statistic']]
                                            ] as $stat)
                                                <div class="list-group-item px-4 py-3 border-light">
                                                    <span class="text-muted small text-uppercase fw-semibold d-block mb-1">{{ $stat['label'] }}</span>
                                                    <div class="d-flex align-items-center">
                                                        <x-dynamic-component :component="$stat['icon']" class="icon color-green me-2" style="width: 18px;"/>
                                                        <span class="h5 mb-0 fw-bold text-dark">{{ $stat['value'] }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </aside>

                        {{-- Right Column: Word Inventory --}}
                        <main class="col-12 col-lg-9">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h4 text-gold fw-bold mb-0">Woorden Inventaris</h2>
                            </div>

                            @if ($relatedArticles->isNotEmpty())
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                    <div class="card-header bg-white border-0 py-4 px-4">
                                        <form action="#woorden" method="GET" class="row g-3">
                                            <div class="col-md-7">
                                                <div class="input-group input-group-lg shadow-sm border rounded-3 overflow-hidden">
                                                    <span class="input-group-text bg-white border-0"><x-heroicon-o-magnifying-glass class="text-muted" style="width:20px;"/></span>
                                                    <input type="text" name="zoekterm" value="{{ request('zoekterm') }}" class="form-control bg-white border-0 ps-0 fs-6" placeholder="Doorzoek dit label...">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="sortering" class="form-select bg-white shadow-sm form-select-lg border rounded-3 fs-6">
                                                    <option value="" @selected(!request('sortering'))>Sortering...</option>
                                                    <option value="alfabetisch" @selected(request('sortering') === 'alfabetisch')>Alfabetisch (A-Z)</option>
                                                    <option value="populariteit" @selected(request('sortering') === 'populariteit')>Meest bekeken</option>
                                                    <option value="recent" @selected(request('sortering') === 'recent')>Recent toegevoegd</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-dark btn-lg w-100 fs-6 fw-bold shadow-sm">Filter</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light border-top border-bottom">
                                            <tr class="">
                                                <th class="ps-4 text-uppercase bg-light-subtle small fw-bold text-muted border-0">Term</th>
                                                <th class="text-uppercase small bg-light-subtle  text-muted border-0">Weergaves</th>
                                                <th colspan="2" class="text-uppercase small bg-light-subtle fw-bold text-muted border-0">Definitie fragment</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($relatedArticles as $article)
                                                <tr>
                                                    <td class="ps-4 py-3">
                                                        <span class="fs-5 fw-bold color-green d-block">{{ $article->word }}</span>
                                                        <span class="text-muted small">Ref: #{{ $article->id }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="fw-bold">{{ toHumanReadableNumber($article->views) }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-muted w-50">
                                                        <p class="mb-0  lh-sm">
                                                            {{ strip_tags(str($article->description)->markdown()->sanitizeHtml()->words(25)) }}
                                                        </p>
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        <a href="{{ route('word-information.show', $article) }}" class="btn btn-sm btn-light border fw-bold text-uppercase px-2" style="font-size: 0.7rem;">
                                                            <x-heroicon-o-eye class="icon me-1"/> Bekijken
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card-footer bg-white py-4 px-4 border-top-0">
                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                            <div class="text-muted small mb-3 mb-md-0">
                                                Overzicht: <span class="text-dark fw-bold">{{ $relatedArticles->firstItem() }} - {{ $relatedArticles->lastItem() }}</span> van <span class="text-dark fw-bold">{{ $relatedArticles->total() }}</span> termen
                                            </div>
                                            <div>{{ $relatedArticles->onEachSide(1)->appends(request()->query())->links() }}</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
                                    <div class="card-body">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <x-heroicon-o-magnifying-glass-circle class="text-muted" style="width: 40px;"/>
                                        </div>
                                        <h4 class="fw-bold">Geen resultaten</h4>
                                        <p class="text-muted mx-auto mb-4" style="max-width: 350px;">We konden geen woorden vinden die voldoen aan de criteria voor dit label.</p>
                                        <a href="{{ url()->current() }}" class="btn btn-outline-dark px-4 fw-bold shadow-sm">Filters wissen</a>
                                    </div>
                                </div>
                            @endif
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
