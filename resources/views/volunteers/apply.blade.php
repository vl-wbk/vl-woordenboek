@extends ('layouts.application-blank', ['title' => 'Registreer als vrijwilliger'])


@section ('content')
    {{--
     |--------------------------------------------------------------------------
     | Volunteer Registration Form
     |--------------------------------------------------------------------------
     |
     | Gebruikersregistratie voor vrijwilligers met focus op rolkeuze en regio.
     | De inputs zijn wit gehouden voor een cleane interface.
     |
     --}}
    <section class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="mb-4">
                    <a href="{{ url()->previous() }}" class="text-decoration-none text-muted small d-flex align-items-center">
                        <x-heroicon-o-chevron-double-left class="icon me-1"/> Terug naar de informatie pagina
                    </a>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-5">
                            <h2 class="fw-bold color-green">Word lid van het Vlaams Woordenboek als {{ strtolower($position->name) }}
                            </h2>
                            <p class="text-muted">Draag je steentje bij aan de documentatie van de Vlaamse taal. Vul je gegevens in en wij nemen snel contact met je op.</p>

                            @if (flash()->message)
                            <div class="alert shadow-sm alert-success mt-3 {{ flash()->class }}">
                                {{ flash()->message }}
                            </div>
                        @endif
                        </div>

                        <form action="{{ route('volunteers.apply', $position) }}" method="POST">
                            @csrf

                            <div class="mb-5">
                                <label class="h5 fw-bold mb-3 d-block text-dark">1. Persoonlijke gegevens</label>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted fw-bold">Voornaam <span class="fw-bold text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg @error('voornaam') is-invalid @enderror" name="voornaam" value="{{ request('voornaam', $user->firstname) }}" placeholder="bijv. Jan">
                                        <x-forms.validation-error field="voornaam"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted fw-bold">Achternaam <span class="fw-bold text-danger">*</span></label>
                                        <input type="text" class="form-control @error('achternaam') is-invalid @enderror form-control-lg" value="{{ old('achternaam', $user->lastname) }}" name="achternaam" placeholder="bijv. Janssens">
                                        <x-forms.validation-error field="achternaam"/>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted fw-bold">E-mailadres <span class="fw-bold text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror form-control-lg" name="email" value="{{ old('email', $user->email) }}" placeholder="naam@voorbeeld.be">
                                        <x-forms.validation-error field="email"/>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="h6 fw-bold mb-1 d-block text-dark">2. Regio expertise</label>
                                <p class="small text-muted mb-3">Selecteer de regio's waar je het meest over kunt vertellen.</p>

                                <div class="d-flex flex-wrap gap-2 region-selector">
                                    @foreach($regions as $regio)
                                        <input type="checkbox" class="btn-check" id="reg_{{ $regio->id }}" name="regio[]" value="{{ $regio->name }}">
                                        <label class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-medium" for="reg_{{ $regio->id }}">
                                            <x:heroicon-o-map class="icon me-1"/>{{ $regio->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="h6 fw-bold mb-3 d-block text-dark">3. Motivatie (optioneel)</label>
                                <textarea class="form-control" name="motivatie" rows="4" placeholder="Waarom wil je ons team komen versterken?">{{ old('motivatie') }}</textarea>
                            </div>

                             <div class="mb-5">
                                <label class="h6 fw-bold mb-3 d-block text-dark">4. Achtergrond (optioneel)</label>
                                <textarea class="form-control" name="achtergrond" rows="4" placeholder="Vertel ons omtrent je achtergrond en of intresse in de Vlaamse taal">{{ old('achtergrond') }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                                <p class="small text-muted mb-0"></p>
                                <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                                    Verzend aanmelding
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
