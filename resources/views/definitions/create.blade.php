@extends('layouts.application-blank', ['title' => 'Nieuwe suggestie'])

@section('jumbotron')
    <header class="position-relative py-5 border-bottom" style="background-color: #fcfaf7;">
        <div class="container-fluid position-relative z-1">
            <div class="row justify-content-center">
                <div class="col-11 col-xl-10">

                    {{-- Status Badge & Breadcrumb --}}
                    <div class="d-flex align-items-center mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small text-uppercase fw-bold tracking-tighter" style="font-size: 0.7rem;">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">Vlaams Woordenboek</a>
                                </li>
                                <li class="breadcrumb-item active text-warning" aria-current="page">Bijdrage Inzenden</li>
                            </ol>
                        </nav>
                    </div>

                    {{-- Hoofdtitel --}}
                    <div class="mb-4">
                        <h1 class="display-4 display-md-2 fw-black mb-2 text-dark" style="letter-spacing: -1px;">
                            Jouw <span class="text-warning">Woord</span> als bijdrage
                        </h1>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning me-3" style="height: 2px; width: 40px;"></div>
                            <span class="text-uppercase small fw-bold tracking-widest text-muted">Protocol voor nieuwe lemma's</span>
                        </div>
                    </div>

                    {{-- Introductie tekst --}}
                    <div class="row">
                        <div class="col-md-10 col-lg-8">
                            <p class="fs-5 text-secondary mb-5 lh-base" style="font-weight: 300;">
                                Dien hier zelf een suggestie in voor een nieuw artikel voor het Vlaams Woordenboek. De redactie beoordeelt en bewerkt alle suggesties voor ze online verschijnen. Wil je weten wat er met jouw suggestie gebeurt? Maak dan een account aan, dan kun je alles van a tot z opvolgen. 
Ter info: lokale en regionale woorden worden alleen opgenomen wanneer ze ook voorkomen in tv-series, literatuur of media. Toon dat aan met voorbeeldzinnen op websites van bijv. een blog, een krant zoals kw.be of een tv-zender zoals tvl.be.
                            </p>

                            {{-- Acties --}}
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <a href="{{ route('home') }}" class="btn btn-dark rounded-1 px-4 py-2 shadow-sm d-flex align-items-center">
                                    <x-heroicon-o-arrow-left class="icon icon-sm me-2"/>
                                    <span class="small text-uppercase fw-bold tracking-wider">Terug naar overzicht</span>
                                </a>
                                <div class="d-flex align-items-center text-muted small border-start ps-3 d-none d-sm-flex">
                                    <x-heroicon-o-shield-check class="icon icon-sm me-2 text-warning"/>
                                    <span>Redactionele controle actief</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Subtiele grid-overlay aan de onderkant --}}
        <div class="position-absolute bottom-0 start-0 w-100 shadow-sm" style="height: 4px; background-image: radial-gradient(#b08d57 1px, transparent 1px); background-size: 20px 20px; opacity: 0.2;"></div>
    </header>
@endsection

@section('content')
    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-11 col-xl-10">

                {{-- Status Melding --}}
                @if (flash()->message)
                    <div class="alert alert-secondary border-0 shadow-sm rounded-3 border-start border-4 mb-5 p-4 {{ (flash()->class === 'text-success') ? 'border-success' : 'border-danger' }}" role="alert">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1 {{ (flash()->class === 'text-success') ? 'text-success' : 'text-danger' }}">
                                    @if(flash()->class === 'text-success')
                                        <x-heroicon-s-check class="icon me-1"/> Suggestie goed ontvangen
                                    @else
                                        <x-heroicon-s-wrench-screwdriver class="icon me-1" /> Er is iets misgelopen!
                                    @endif
                                </h6>
                                <p class="mb-0 text-secondary">{{ flash()->message }}</p>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <div class="container-fluid px-0">
                    <div class="row">
                        <form action="" class="col-8">
                            <div class="card bg-white rounded-3 shadow-sm p-4 border-0">
                                <div class="card-body p-0">
                                    {{-- SEction 1: suggestion - base information --}}
                                    <section class="border-bottom pb-4">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="rounded-circle border border-info bg-info bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px;">
                                                <span class="small text-info fw-bold">1</span>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-0">Wat is je suggestie?</h5>
                                                <small class="text-muted">Definieer de bases en grammatica van de suggestie</small>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="woord" class="form-label">Woord of uitdrukking <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control rounded-3" id="woord" placeholder="Vul het woord of de uitdrukking in">
                                                <div class="form-text text-muted" style="font-size: 0.75rem;">Het woord waar je suggestie over gaat.</div>
                                            </div>

                                            <div class="col-6">
                                                <label for="woord" class="form-label">Woordsoort <span class="text-danger">*</span></label>
                                                <select name="" id="" class="form-select rounded-3">
                                                    <option value="">-- woordsoort --</option>
                                                </select>
                                            </div>

                                            <div class="col-6">
                                                <label for="woord" class="form-label">Kenmerken <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control rounded-3" id="woord" placeholder="bijv. de ~ (v.), -s" value="-">
                                                <div class="form-text text-muted" style="font-size: 0.75rem;">Het woord waar je suggestie over gaat.</div>
                                            </div>

                                            <div class="col-12">
                                                <label for="woord" class="form-label">Beschrijving <span class="text-danger">*</span></label>
                                                <textarea class="form-control rounded-3" rows="4" id="woord" placeholder="Beschrijf je suggestie zo duidelijk mogelijk. Wat is de betekenis, het gebruik, ..."></textarea>
                                                <div class="form-text text-muted" style="font-size: 0.75rem;">Geef zoveel mogelijk details zodat onze redacteurs je suggestie goed kunnen beoordelen.</div>
                                            </div>
                                        </div>
                                    </section>

                                    <section class="border-bottom pb-4">
                                        <div class="d-flex align-items-center my-4">
                                            <div class="rounded-3 shadow-sm border border-info bg-info bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px;">
                                                <x-heroicon-o-map class="icon text-info"/>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-0">Regionale informatie</h5>
                                                <small class="text-muted">In welke regio of regionale context hebt je uw suggestie gehoord?</small>
                                            </div>
                                        </div>

                                        <div class="g-3">

                                        </div>
                                    </section>

                                    <section class="border-bottom pb-3">
                                        <div class="d-flex align-items-center mt-3 mb-4">
                                            <div class="rounded-3 shadow-sm border border-info bg-info bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px;">
                                                <x-heroicon-o-map class="icon text-info"/>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-0">Voorbeeldzinnen</h5>
                                                <small class="text-muted">In welke regio of regionale context hebt je uw suggestie gehoord?</small>
                                            </div>
                                        </div>
                                    </section>

                                    <section>
                                        <div class="d-flex align-items-center my-4">
                                            <div class="rounded-3 shadow-sm border border-info bg-info bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px;">
                                                <x-heroicon-o-map class="icon text-info"/>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-0">Over jouw</h5>
                                                <small class="text-muted">Je kunt deze suggestie ook anoniem indienen.</small>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <!-- Name and Email Inputs -->
                                            <div class="col-md-12">
                                                <label for="naam" class="form-label">Naam (of bijnaam)</label>
                                                <input type="text" class="form-control rounded-3" id="naam" placeholder="Jouw naam of bijnaam">
                                            </div>

                                            <div class="col-12">
                                                <!-- Toggle Option Box -->
                                                <div class="card bg-light border-secondary rounded-3 p-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center">
                                                            <div class=" me-3">
                                                                <x-heroicon-o-user class="icon" />
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-bold mb-1 small">Ik wil mijn naam toevoegen</h6>
                                                                <p class="text-muted mb-0" style="font-size: 0.75rem;">Laat je naam zien bij deze suggestie</p>
                                                            </div>
                                                        </div>
                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input bg-white" type="checkbox" role="switch" id="toevoegenNaamSwitch" style="width: 2.5em; height: 1.25em; cursor: pointer;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>

                            <hr>

                            <div class="card bg-white border-0 shadow-sm rounded-3 p-4">
                                <div class="card-body p-0 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                                    <!-- Left Side: Icon, Title, and Description -->
                                    <div class="d-flex align-items-start">
                                        <div class="bg-danger bg-opacity-25 p-3 rounded-3 me-3 text-light d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                            <x-tabler-heart-handshake class="text-danger icon" />
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Dankjewel!</h6>
                                            <p class="text-muted small mb-0">Je suggestie zal met de nodige zorg worden nagekeken.</p>
                                        </div>
                                    </div>

                                    <!-- Right Side: Action Buttons -->
                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        <button type="button" class="btn btn-outline-danger px-4 py-2 rounded-3">
                                            Annuleren
                                        </button>
                                        <button type="button" class="btn btn-success bg-opacity-20 px-4 py-2 rounded-3 d-flex align-items-center gap-2">
                                            <span>Suggestie indienen</span>
                                            <x-heroicon-o-paper-airplane class="icon" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        {{-- Sidenav --}}
                        <div class="col-4">
                            <div class="card bg-white border-0 shadow-sm rounded-3 p-4 mb-4">
                                <div class="card-body p-0">
                                    <h5 class="fw-bold color-green mb-4">Hoe werkt het?</h5>

                                    {{-- Step 1 --}}
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-info bg-opacity-10 shadow-sm p-3 rounded-3 me-3 text-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <x-heroicon-o-light-bulb class="text-info icon" />
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">1. Indienen</h6>
                                            <p class="small mb-0">Vul het formulier in met jouw suggestie.</p>
                                        </div>
                                    </div>

                                    {{-- Step 2 --}}
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-info bg-opacity-10 shadow-sm p-3 rounded-3 me-3 text-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <x-heroicon-o-users class="icon text-info" />
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">2. Beoordeling</h6>
                                            <p class="small mb-0">Onze redacteurs bekijken je suggestie zorgvuldig.</p>
                                        </div>
                                    </div>

                                    {{-- Step 3 --}}
                                    <div class="d-flex align-items-start">
                                        <div class="bg-info bg-opacity-10 shadow-sm p-3 rounded-3 me-3 text-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <x-heroicon-o-check class="icon text-info" />
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">3. Verwerking</h6>
                                            <p class="smalll mb-0">Bij goedkeuring wordt je suggestie toegevoegd aan het woordenboek.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card bg-white border-0 shadow-sm rounded-3 p-4 mb-4">
                                <div class="card-body p-0">
                                    <h5 class="fw-bold color-green mb-4">Tips voor een goede suggestie</h5>

                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex align-items-center mb-3 small">
                                            <x-heroicon-o-check-circle class="text-success me-2 icon flex-shrink-0" />
                                            Wees zo specifiek mogelijk
                                        </li>
                                        <li class="d-flex align-items-center mb-3 small">
                                            <x-heroicon-o-check-circle class="text-success me-2 icon flex-shrink-0" />
                                            Geef duidelijke voorbeelden.
                                        </li>
                                        <li class="d-flex align-items-center mb-3 text-muted small">
                                            <x-heroicon-o-check-circle class="text-success me-2 icon flex-shrink-0" />
                                            Vermeld indien mogelijk de herkomst of regio.
                                        </li>
                                        <li class="d-flex align-items-center mb-3 text-muted small">
                                            <x-heroicon-o-check-circle class="text-success me-2 icon flex-shrink-0" />
                                            Controleer of het woord nog niet bestaat in ons woordenboek.
                                        </li>
                                        <li class="d-flex align-items-center text-muted small">
                                            <x-heroicon-o-check-circle class="text-success me-2 icon flex-shrink-0" />
                                            Suggesties worden publiek zichtbaar na beoordeling.
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card bg-white border-0 shadow-sm rounded-3 p-4 mb-4">
                                <div class="card-body p-0">
                                    <h5 class="fw-bold color-green mb-2">Eerder ingediende suggesties</h5>
                                    <p class="small mb-4">Bekijk de status van je eerder ingediende suggesties.</p>
                                    
                                    <a href="#" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-between rounded-3 border-secondary">
                                        <span class="fw-medium">Mijn suggesties bekijken</span>
                                        <x-heroicon-o-arrow-right class="icon-sm" />
                                    </a>
                                </div>
                            </div>

                            <div class="card bg-white border-0 shadow-sm rounded-3 p-4">
                                <div class="card-body p-0">
                                    <h5 class="fw-bold color-green mb-2">Hulp nodig?</h5>
                                    <p class="text-muted small mb-4">Heb je vragen over het indienen van een suggestie? Neem gerust contact met ons op.</p>
                                    
                                    <a href="#" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-between rounded-3 border-secondary">
                                        <span class="fw-medium">Contact opnemen</span>
                                        <x-heroicon-o-arrow-right class="icon-sm" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Citeerhulp --}}
    <div class="modal fade" id="sourceInformation" tabindex="-1" aria-labelledby="sourceInformationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-3 border-0 shadow-lg">

                <div class="modal-header bg-dark text-white border-0 p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning p-2 me-3 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                            <x-heroicon-s-book-open class="icon text-dark" style="width: 1.5rem; height: 1.5rem;"/>
                        </div>
                        <div>
                            <h1 class="modal-title fs-5 fw-bold mb-0" id="sourceInformationLabel">
                                Bronvermelding bij <span class="text-warning">voorbeeldzinnen</span>
                            </h1>
                            <small class="text-white-50 text-uppercase tracking-widest fw-bold" style="font-size: 0.65rem;">Lexicografische Standaard</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 p-md-5" style="background-color: #fcfaf7;">
                    <p class="lead fw-bold text-dark mb-3 fs-6">
                        Voorbeeldzinnen zijn essentieel om de nuance en context van een woord te illustreren.
                    </p>
                    <p class="text-secondary lh-base mb-4 small">
                        Onze voorkeur gaat uit naar citaten uit (online) bronnen, zoals blogs, kranten, tijdschriftartikels of sociale media.
                        <span class="text-dark fw-bold fst-italic">Alleen als het echt niet anders kan</span> (bijv. bij zeldzame dialecten), kun je een zelfverzonnen zin opgeven.
                    </p>

                    <div class="d-flex flex-column gap-4">
                        {{-- 01. Artikels --}}
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-dark rounded-1 me-2">01</span>
                                <h6 class="fw-bold mb-0 text-dark">Uit een artikel</h6>
                            </div>
                            <p class="small text-muted mb-2 fst-italic">Structuur: (bron: auteur – titel – bron – datum publicatie – ‘geraadpleegd op’ datum)</p>

                            <div class="bg-white p-3 border-start border-3 border-warning shadow-sm mb-2 rounded-end">
                                <p class="small mb-1 text-secondary fst-italic">"U voelt meteen stront aan de knikker in ‘Malditos’"</p>
                                <span class="text-dark fw-bold" style="font-size: 0.8rem;">(Bron: titel in De Morgen 5.05.2025, geraadpleegd op 14.05.2025)</span>
                            </div>
                        </div>

                        {{-- 02. Website --}}
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-dark rounded-1 me-2">02</span>
                                <h6 class="fw-bold mb-0 text-dark">Uit een website</h6>
                            </div>
                            <div class="bg-white p-3 border-start border-3 border-warning shadow-sm rounded-end">
                                <p class="small mb-1 text-secondary fst-italic">"Goesting in Antwerpen? Wij gidsen je op een plezante manier."</p>
                                <span class="text-dark fw-bold" style="font-size: 0.8rem;">(Bron: Goesting in A, geraadpleegd op 14.05.2025)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4" style="background-color: #fcfaf7;">
                    <button type="button" class="btn btn-dark px-4 py-2 fw-bold small text-uppercase tracking-widest rounded-2" data-bs-dismiss="modal">Begrepen</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('kv-container');
        const btnAdd = document.getElementById('add-pair');

        function updateRemoveButtons() {
            const rows = container.querySelectorAll('.kv-row');
            const btns = container.querySelectorAll('.remove-row');

            btns.forEach(btn => {
                if (rows.length === 1) {
                    btn.disabled = true;
                    btn.classList.add('opacity-25');
                } else {
                    btn.disabled = false;
                    btn.classList.remove('opacity-25');
                }
            });
        }

        function reindexVoorbeeldzinRows() {
            container.querySelectorAll('.kv-row').forEach((row, i) => {
                row.querySelectorAll('input, textarea').forEach(field => {
                    field.name = field.name.replace(/\[\d+\]/, `[${i}]`);
                });
            });
        }

        btnAdd.addEventListener('click', () => {
            const rows = container.querySelectorAll('.kv-row');
            const clone = rows[0].cloneNode(true);

            clone.querySelectorAll('input, textarea').forEach(field => {
                field.value = '';
                field.classList.remove('is-invalid');
            });
            clone.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            container.appendChild(clone);
            reindexVoorbeeldzinRows();
            updateRemoveButtons();
        });

        container.addEventListener('click', e => {
            const removeBtn = e.target.closest('.remove-row');
            if (removeBtn && !removeBtn.disabled) {
                const rows = container.querySelectorAll('.kv-row');
                if (rows.length > 1) {
                    removeBtn.closest('.kv-row').remove();
                    reindexVoorbeeldzinRows();
                    updateRemoveButtons();
                }
            }
        });

        // Initialize button states on load
        updateRemoveButtons();
    });
</script>
@endsection

@section('styles')
    <style>
        textarea.resizable {
            resize: vertical !important;
            min-height: 2.5rem;
        }
        /* Kleine toevoeging voor een soepele hover op de verzendknop */
        .hover-scale:hover {
            transform: translateY(-1px);
        }
    </style>
@endsection
