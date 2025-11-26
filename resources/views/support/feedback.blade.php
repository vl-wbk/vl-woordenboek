@extends('layouts.application-blank', ['title' => __('pages/feedback.page-title')])

@section('content')
    <div class="my-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 bg-white shadow-sm">
                        <div class="card-header bg-white">
                            <h4 class="card-title color-green mb-1">{{ __('pages/feedback.page-heading') }}</h4>
                            <h6 class="text-muted mb-0">{{ __('pages/feedback.page-description') }}</h6>
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
                                    <label for="name" class="col-form-label">{{ __('pages/feedback.form.labels.name') }}<span
                                            class="fw-bold text-danger">*</span></label>
                                    <input type="text" name="naam" id="name" value="{{ old('naam', auth()->user()?->name) }}"
                                           class="form-control @error('naam') is-invalid @enderror">
                                    <x-forms.validation-error field="naam" />
                                </div>

                                <div class="form-group col-6 mb-3">
                                    <label for="emailAddress" class="col-form-label">{{ __('pages/feedback.form.labels.email') }}</label>
                                    <input type="email" name="email" class="form-control" id="emailHelpText"
                                           value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6 mb-3">
                                    <label for="firstTimeVisitor" class="col-form-label">{{ __('pages/feedback.form.labels.first-visit') }} <span class="fw-bold text-danger">*</span></label>

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
                                            {{ __('pages/feedback.validation-errors.required') }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-6 mb-3">
                                    <label for="yoda" class="col-form-label">{{ __('pages/feedback.form.labels.easy-find') }}<span
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
                                            {{ __('pages/feedback.validation-errors.required') }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-6 mb-3">
                                    <label for="visitReason" class="col-form-label">{{ __('pages/feedback.form.labels.visit-reason') }}</label>
                                    <textarea name="bezoek_redenen" class="form-control" id="visitreason" rows="4">{{ old('bezoek_redenen') }}</textarea>
                                </div>

                                <div class="form-group col-6 mb-3">
                                    <label for="extraInformationResults" class="col-form-label">{{ __('pages/feedback.form.labels.search-improvement') }}</label>
                                    <textarea name="extra_informatie_zoektocht" class="form-control" id="extraInformationResults" rows="4">{{ old('extra_informatie_zoektocht') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group col-12 mb-3">
                                <label for="suggestion" class="col-form-label">{{ __('pages/feedback.form.labels.other-improvements') }}</label>
                                <textarea name="extra_informatie" class="form-control" id="suggestion" rows="4">{{ old('extra_informatie') }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="contact" role="switch"
                                           value="1">
                                    <label class="form-check-label ms-1" for="switchCheckCheckedDisabled">{{ __('pages/feedback.form.switch.can-contact') }}</label>
                                </div>
                            </div>
                        </form>

                        <div class="card-footer bg-white">
                            <button type="submit" form="suggestionForm" class="btn btn-sm btn-submit">
                                {{ __('pages/feedback.form.buttons.submit') }}
                            </button>
                            <button type="reset" form="suggestionForm" class="btn btn-sm btn-link">
                                {{ __('pages/feedback.form.buttons.reset') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
