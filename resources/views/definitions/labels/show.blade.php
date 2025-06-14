@extends ('layouts.application-blank', ['title' => 'Label informatie'])

@section ('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 border-bottom border-green pb-2">
            <div>
                <h1 class="display-5 d-flex align-items-center justify-content-between">
                    <span>Label: <span class="color-green">Dialect</span></span>

                    <div class="dropdown flex-shrink-0">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <x-heroicon-o-arrows-right-left class="icon me-1"/> ander label bekijken
                        </button>
                        <ul class="dropdown-menu border-0 shadow-sm dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                </h1>
                <div class="pb-3">
                    <span class="badge shadow-sm bg-info fs-6 me-2">
                        <x-heroicon-s-book-open class="icon shadow-sm me-1"/>152 Woorden
                    </span>
                    <a href="" class="badge bg-danger text-white text-decoration-none text-dark fs-6">
                        <x-heroicon-s-document-text class="icon me-1"/>Populairste: <strong>plezant</strong></a>
                </div>
                <p class="text-muted pb-2">Woorden en uitdrukkingen typisch voor het Vlaamse dialect. Woorden en uitdrukkingen typisch voor het Vlaamse dialect. Woorden en uitdrukkingen typisch voor het Vlaamse dialect. Woorden en uitdrukkingen typisch voor het Vlaamse dialect. Woorden en uitdrukkingen typisch voor het Vlaamse dialect.</p>
                <small class="text-secondary">Aangemaakt op 15 maart 2024 | Laatst bijgewerkt op 12 juni 2025</small>
            </div>
        </div>

        <section class="mb-5">
            <h2 class="mb-4 text-gold">Deze taalkundige regio in cijfers </h2>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card information-statistic text-white border-0 shadow p-1">
                        <div class="card-body">
                            <h5 class="card-title">Aantal weergaves</h5>
                            <p class="display-6">12.345</p>
                            <small class="pt-2">Laatste correctie: 10 juni</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card information-statistic text-white shadow p-1">
                        <div class="card-body">
                            <h5 class="card-title">Aantal woorden</h5>
                            <p class="display-6">8.912</p>
                            <small class="pt-2">Laatste correctie: 10 juni</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card information-statistic text-white border-0 shadow p-1">
                        <div class="card-body">
                            <h5 class="card-title">Aantal gemeentes</h5>
                            <p class="display-6">3m 27s</p>
                            <small class="pt-2">Laatste correctie: 10 juni</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card information-statistic text-white border-0 shadow p-1">
                        <div class="card-body">
                            <h5 class="card-title">Aantal upvotes</h5>
                            <p class="display-6">26%</p>
                            <small class="pt-2">Laatste correctie: 10 juni</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        {{-- Technische grammaticale info --}}
        <section class="mb-5">
            <h2 class="mb-4 text-gold">Technische informatie – Beringerlands</h2>
            <ul class="list-group border-0 shadow-sm">
                <li class="list-group-item">
                    <strong>Labeltype:</strong> <span class="float-end">Regionaal / Dialectaal</span>
                </li>
                <li class="list-group-item">
                    <strong>Regio:</strong> <span class="float-end">Beringen en omliggende gemeenten (bijv. Beverlo, Paal, Koersel)</span>
                </li>
                <li class="list-group-item">
                    <strong>Taalvariëteit:</strong> <span class="float-end">Limburgs dialect met Kempense invloeden</span>
                </li>
                <li class="list-group-item">
                    <strong>Vormvariatie:</strong> <span class="float-end">Ja — duidelijke verschillen tussen jongere en oudere sprekers; invloeden door migratie</span>
                </li>
                <li class="list-group-item">
                    <strong>Uitspraakkenmerken:</strong> <span class="float-end">Kenmerkende nasale klanken, diftongen, en zangerige intonatie</span>
                </li>
                <li class="list-group-item">
                    <strong>Status:</strong> <span class="float-end"> bedreigd afname bij jongere generatie</span>
                </li>
            </ul>
        </section>

        {{-- Zoekfunctie gekoppelde woorden --}}
        <section class="mb-5">
            <h2 class="mb-4 text-gold">Gekoppelde woorden</h2>

            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom-0 bg-sidenav">
                    <form class="row g-2">
                        <div class="col-9">
                            <input type="text" class="form-control w-100" placeholder="Zoek op woord of betekenis…">
                        </div>
                        <div class="col-2">
                            <select class="form-select">
                                <option selected>Sorteren op</option>
                                <option>Alfabetisch</option>
                                <option>Populariteit</option>
                                <option>Meest recent</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <button class="btn w-100 btn-submit">Zoeken</button>
                        </div>
                    </form>
                </div>

                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th class="border-top-0 color-green">Woord</th>
                                    <th class="border-top-0 color-green">Beschrijving</th>
                                    <th class="border-top-0 color-green">Upvotes</th>
                                    <th class="border-top-0 color-green" colspan="2">Views</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="card-footer border-top-0 bg-sidenav">

                </div>
            </div>
        </section>
    </div>
@endsection
