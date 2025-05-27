@extends('layouts.application-blank', ['title' => 'Feedback insturen'])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="card-title color-green mb-1">Website feedback</h4>
                        <h6 class="text-muted mb-0">Omdat het Vlaams woordenboek continu willen verbeteren en uw mening
                            daarin telt, kunt u hier uw feedback deponeren zodat de kern ploeg ermee aan de slag kan gaan.
                        </h6>
                    </div>
                    <form action="{{ route('feedback:store') }}" method="POST" id="suggestionForm" class="card-body">
                        @csrf

                        <div class="row">
                            <div class="form-group col-6 mb-3">
                                <label for="name" class="col-form-label">Voor + achternaam <span
                                        class="fw-bold text-danger">*</span></label>
                                <input type="text" name="naam" id="name" value="{{ old('naam', auth()->user()?->name) }}"
                                    class="form-control @error('naam') is-invalid @enderror">
                                <x-forms.validation-error field="naam" />
                            </div>

                            <div class="form-group col-6 mb-3">
                                <label for="emailAddress" class="col-form-label">Email adres</label>
                                <input type="email" name="email" class="form-control" id="emailHelpText"
                                    value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-6 mb-3">
                                <label for="firstTimeVisitor" class="col-form-label">Dit was de eerste keer dat ik het
                                    Vlaams Woordenboek bezocht. <span class="fw-bold text-danger">*</span></label>

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
                                <label for="yoda" class="col-form-label">Ik kon makkelijk vinden wat ik zocht. <span
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
                                <label for="visitReason" class="col-form-label">Wat wat was primaire redenen waarom je het
                                    woordenboek bezocht?</label>
                                <textarea name="bezoek_redenen" class="form-control" id="visitreason" rows="4">{{ old('bezoek_redenen') }}</textarea>
                            </div>

                            <div class="form-group col-6 mb-3">
                                <label for="extraInformationResults" class="col-form-label">Kunt u ons vertellen wat er
                                    beter kon in uw zoektocht?</label>
                                <textarea name="extra_informatie_zoektocht" class="form-control" id="extraInformationResults" rows="4">{{ old('extra_informatie_zoektocht') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group col-12 mb-3">
                            <label for="suggestion" class="col-form-label">Ik heb nog andere opmerkingen of andere
                                suggesties omtrent hoe het Vlaams Woordenboek verbeterd kan worden.</label>
                            <textarea name="extra_informatie" class="form-control" id="suggestion" rows="4">{{ old('extra_informatie') }}</textarea>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="contact" role="switch"
                                    value="1">
                                <label class="form-check-label ms-1" for="switchCheckCheckedDisabled">De beheerders van het
                                    Vlaams woordenboek mogen contact opnemen met me indien zij dat nodig achten.</label>
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
