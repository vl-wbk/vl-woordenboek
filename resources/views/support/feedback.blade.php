@extends('layouts.application-blank', ['title' => 'Feedback insturen'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="card-title color-green mb-1">Website feedback</h4>
                        <h6 class="text-muted mb-0">We willen het Vlaams Woordenboek continu verbeteren. Daarvoor hebben we feedback nodig, ook die van jou. Vertel ons hier wat volgens jou beter kan, dan gaat de redactie ermee aan de slag.
                        </h6>
                    </div>
                    <form action="{{ route('feedback:store') }}" method="POST" id="suggestionForm" class="card-body">
                        @csrf

                        @if (flash()->message)
                            <div class="shadow-sm border-0 py-2 px-3 {{ flash()->class }}" role="alert">
                                {{  flash()->message }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="form-group col-6 mb-3">
                                <label for="name" class="col-form-label">Voor- en achternaam <span
                                        class="fw-bold text-danger">*</span></label>
                                <input type="text" name="naam" id="name" value="{{ old('naam', auth()->user()?->name) }}"
                                    class="form-control @error('naam') is-invalid @enderror">
                                <x-forms.validation-error field="naam" />
                            </div>

                            <div class="form-group col-6 mb-3">
                                <label for="emailAddress" class="col-form-label">E-mailadres</label>
                                <input type="email" name="email" class="form-control" id="emailHelpText"
                                    value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-6 mb-3">
                                <label for="firstTimeVisitor" class="col-form-label">Was dit je eerste bezoek aan het Vlaams Woordenboek? <span class="fw-bold text-danger">*</span></label>

                                <div>
                                    @foreach ($radioButtons::cases() as $radioButton)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('eerste_bezoek') is-invalid @enderror"
                                                type="radio" name="eerste_bezoek" id="inlineRadio1"
                                                value="{{ $radioButton->value }}" @checked(old('eerste_bezoek', null) === $radioButton->value)>
                                            <label class="form-check-label"
                                                for="inlineRadio1">{{ $radioButton->getLabel() }}</label>
                                        </div>
                                    @endforeach

                                    @error('eerste_bezoek')
                                        <div class="invalid-feedback fw-bold d-block" role="alert">
                                            {{ __('Dit moet ingevuld zijn alvorens de feedback te kunnen verzenden') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-6 mb-3">
                                <label for="yoda" class="col-form-label">Kon je makkelijk vinden wat je zocht?. <span
                                        class="fw-bold text-danger">*</span></label>

                                <div>
                                    @foreach ($radioButtons::cases() as $radioButton)
                                        <div class="form-check form-check-inline">
                                            <input
                                                class="form-check-input @error('resultaten_gevonden') is-invalid @enderror"
                                                type="radio" name="resultaten_gevonden" id="inlineRadio1"
                                                value="{{ $radioButton->value }}" @checked(old('resultaten_gevonden', null) === $radioButton->value)>
                                            <label class="form-check-label"
                                                for="inlineRadio1">{{ $radioButton->getLabel() }}</label>
                                        </div>
                                    @endforeach

                                    @error('resultaten_gevonden')
                                        <div class="invalid-feedback fw-bold d-block" role="alert">
                                            {{ __('Dit moet ingevuld zijn alvorens de feedback te kunnen verzenden') }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-6 mb-3">
                                <label for="visitReason" class="col-form-label">Wat is de belangrijkste reden waarom je deze website bezoekt?</label>
                                <textarea name="bezoek_redenen" class="form-control" id="visitreason" rows="4">{{ old('bezoek_redenen') }}</textarea>
                            </div>

                            <div class="form-group col-6 mb-3">
                                <label for="extraInformationResults" class="col-form-label">Wat kon er beter in je zoektocht?</label>
                                <textarea name="extra_informatie_zoektocht" class="form-control" id="extraInformationResults" rows="4">{{ old('extra_informatie_zoektocht') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group col-12 mb-3">
                            <label for="suggestion" class="col-form-label">Heb je nog andere opmerkingen of suggesties waarmee we het Vlaams Woordenboek kunnen verbeteren?</label>
                            <textarea name="extra_informatie" class="form-control" id="suggestion" rows="4">{{ old('extra_informatie') }}</textarea>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="contact" role="switch"
                                    value="1">
                                <label class="form-check-label ms-1" for="switchCheckCheckedDisabled">Het beheer van het Vlaams Woordenboek mag me contacteren als dat nodig is. (Indien ja: geef een mailadres op)</label>
                            </div>
                        </div>
                    </form>

                    <div class="card-footer bg-white">
                        <button type="submit" form="suggestionForm" class="btn btn-sm btn-submit">
                            Verzenden
                        </button>
                        <button type="reset" form="suggestionForm" class="btn btn-sm btn-link">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
