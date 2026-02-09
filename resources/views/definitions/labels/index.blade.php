
    @extends('layouts.application-blank', ['title' => 'Labels Overzicht'])

    @section('jumbotron')
        <header class="bg-light bg-blend-hard-light border-bottom shadow-sm">
            <div class="container-fluid py-5">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <div class="row align-items-end">
                            <div class="col-lg-7">
                                <nav aria-label="breadcrumb" class="mb-3">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/') }}">
                                                    <x-heroicon-o-home class="icon me-1" />Home
                                                </a>
                                            </li>

                                            <li class="breadcrumb-item active" aria-current="page">
                                                Labels
                                            </li>
                                        </ol>
                                    </nav>
                                </nav>

                                <h1 class="display-3 fw-black text-dark mb-3">Labels</h1>
                                <p class="lead text-secondary mb-0">
                                    Een gestructureerd overzicht van alle taalkundige classificaties en metadata.
                                </p>
                            </div>

                            <div class="col-lg-5 mt-4 mt-lg-0 text-lg-end">
                                <div class="d-inline-flex bg-white shadow-sm p-3 rounded-4 border">
                                    <div class="px-3 border-end">
                                        <span class="text-muted smallest text-uppercase fw-bold d-block">Labels</span>
                                        <span class="h4 mb-0 fw-black">{{ $labels->total() }}</span>
                                    </div>

                                    <div class="px-3">
                                        <span class="text-muted smallest text-uppercase fw-bold d-block">Items</span>
                                        <span class="h4 mb-0 fw-black text-success">{{ $labels->sum('articles_count') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    @endsection

    @section('content')
        <div class="py-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-10">

                        {{-- Filter & Search Bar --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden border-start border-gold border-4">
                            <div class="card-body p-3 bg-white">
                                <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-center">
                                    <div class="col-md-7">
                                        <div class="input-group input-group-sm border rounded-3 overflow-hidden focus-within-gold">
                                        <span class="input-group-text bg-white border-0">
                                            <x-heroicon-o-magnifying-glass class="text-muted" style="width:16px;"/>
                                        </span>
                                            <input type="text" name="zoekterm" value="{{ request('zoekterm') }}"
                                                   class="form-control border-0 ps-0 shadow-none"
                                                   placeholder="Doorzoek het archief...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="sortering" class="form-select form-select-sm border rounded-3 shadow-none border-light" onchange="this.form.submit()">
                                            <option value="naam" @selected(request('sortering') === 'naam')>Alfabetisch (A-Z)</option>
                                            <option value="woorden" @selected(request('sortering') === 'woorden')>Volume (Items)</option>
                                            <option value="recent" @selected(request('sortering') === 'recent')>Laatst gewijzigd</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold shadow-sm">
                                            Filteren
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Dossier Grid --}}
                        <div class="row g-4">
                            @forelse($labels as $label)
                                <div class="col-md-6 col-xl-4">
                                    <a href="{{ route('label:show', $label) }}" class="text-decoration-none group">
                                        <div class="card h-100 border-0 rounded-4 position-relative shadow-sm bg-white dossier-base">

                                            <div class="dossier-tab bg-gold-soft"></div>

                                            <div class="card-body p-4 pt-4 d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-light text-gold fw-bold smallest text-uppercase px-2 py-1 rounded-1 d-flex align-items-center">
                                                    @if(($label->type ?? 'Algemeen') === 'Algemeen')
                                                        <x-heroicon-s-tag class="me-1" style="width: 12px; height: 12px;"/>
                                                    @else
                                                        <x-heroicon-s-squares-2x2 class="me-1" style="width: 12px; height: 12px;"/>
                                                    @endif
                                                    {{ $label->type ?? 'Algemeen' }}
                                                </span>
                                                    <div class="badge-count bg-dark text-white rounded-pill px-3 py-1 fw-bold smallest">
                                                        {{ $label->articles_count ?? 0 }} <span class="fw-normal opacity-75">items</span>
                                                    </div>
                                                </div>

                                                <h3 class="h5 fw-black text-dark mb-2">
                                                    {{ $label->name }}
                                                </h3>

                                                <p class="text-muted smallest lh-base text-truncate-3 mb-4 flex-grow-1">
                                                    @if($label->description)
                                                        {{ $label->description }}
                                                    @else
                                                        Dit label bevat taalkundige metadata en gekoppelde fragmenten
                                                    @endif
                                                </p>

                                                <div class="mt-auto pt-3 border-top border-light-subtle d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center text-muted smallest">
                                                        <x-heroicon-s-clock class="me-1" style="width: 14px;"/>
                                                        <span>{{ $label->updated_at->format('d/m/Y') }}</span>
                                                    </div>

                                                    <div class="text-dark smallest fw-black text-uppercase d-flex align-items-center">
                                                        Openen
                                                        <x-heroicon-s-chevron-right class="text-gold ms-1" style="width: 14px;"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="py-5 text-center bg-white rounded-4 border border-dashed">
                                    <p class="text-muted mb-0">Geen labels gevonden.</p>
                                </div>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-5 d-flex justify-content-center">
                            {{ $labels->onEachSide(1)->appends(request()->query())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>


    <style>
        /* --- Statisch Dossier Systeem --- */
        .group {
            display: block;
            height: 100%;
        }

        .dossier-base {
            overflow: visible !important;
            border: 1px solid rgba(0,0,0,0.05) !important;
        }

        /* De Dossier Tab (Altijd zichtbaar en vast) */


        /* Hover: Alleen een subtiele kleurverandering van de tekst, geen beweging */
        .group:hover .h5 {
            color: #d4af37 !important;
        }

        .group:hover .dossier-base {
            box-shadow: 0 10px 20px rgba(0,0,0,0.06) !important;
            border-color: #d4af37 !important;
        }

        /* Utilities */
        .smallest { font-size: 0.75rem; }
        .fw-black { font-weight: 900; }
        .ls-1 { letter-spacing: 0.05em; }

        .text-truncate-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .focus-within-gold:focus-within {
            border-color: #d4af37 !important;
        }
    </style>
@endsection
