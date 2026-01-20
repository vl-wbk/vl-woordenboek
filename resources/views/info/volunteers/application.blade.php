@extends ('layouts.application-blank', ['title' => __('pages/volunteers-form.page.title')])

@section('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class="py-5">
                <div class="row">
                    <h1 class="display-6 fw-bold">
                        {{ __('pages/volunteers-form.page.title') }}
                    </h1>

                    <p class="col-12 fs-5 text-muted">{{ __ ('pages/volunteers-form.page.introduction')}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 bg-white shadow-sm">
                    @if (flash()->message)
                        <div class="card-header border-0 {{ flash()->class }}">
                            <x-heroicon-o-check-badge class="icon me-1"/> {{ flash()->message }}
                        </div>
                    @endif

                    <form id="applicationForm" method="POST" action="{{ route('support.volunteers.store') }}" class="card-body">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fw-bold mb-3">
                                    {{ __('pages/volunteers-form.labels.position') }}
                                </label>
                            </div>
                        </div>

                        <div class="row g-3"> {{-- g-3 adds consistent spacing between grid items --}}
                            @foreach ($positions as $role)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input @error('positie') is-invalid @enderror" type="radio" name="positie" value="{{ $role->value }}" id="pos_{{ $loop->index }}" @checked($position->value === $role->value || old('positie') == $role->value)>
                                        <label class="form-check-label" for="pos_{{ $loop->index }}">
                                            <span class="d-block fw-bold">{{ $role->getLabel() }}</span>
                                            <small class="text-muted d-block">{{ $role->getDescription() }}</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12">
                                <label for="motivatie" class="form-label fw-bold">{{ __('pages/volunteers-form.labels.motivation')}} <span class="fw-bold text-danger">*</span></label>
                                <textarea 
                                    id="motivatie" 
                                    name="motivatie" 
                                    class="form-control @error('motivatie') is-invalid @enderror" 
                                    rows="5" 
                                    placeholder="{{ __('pages/volunteers-form.placeholders.motivation', ['app' => config('app.name', 'Laravel')]) }}">{{ old('motivatie') }}</textarea>
                                <x-forms.validation-error field="motivatie"/>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="background" class="form-label fw-bold">{{ __('pages/volunteers-form.labels.background') }} <span class="fw-bold text-danger">*</span></label>
                                <textarea
                                    id="background"
                                    name="achtergrond"
                                    class="form-control @error('achtergrond') is-invalid @enderror"
                                    rows="5"
                                    placeholder="{{ __('pages/volunteers-form.placeholders.background') }}"
                                >{{ old('achtergrond') }}</textarea>
                                <x-forms.validation-error field="achtergrond"/>
                            </div>
                        </div>
                    </form>

                    <div class="card-footer border-top-0 bg-white">
                        <button type="submit" form="applicationForm" class="btn btn-sm btn-submit shadow-sm">
                            <x-heroicon-o-paper-airplane class="icon me-1" style="width:1.2rem;"/> {{ __('pages/volunteers-form.buttons.submit') }}
                        </button>

                        <button type="reset" form="applicationForm" class="btn btn-sm btn-link text-decoration-none shadow-none text-muted">
                            {{ __('pages/volunteers-form.buttons.reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection