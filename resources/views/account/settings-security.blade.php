@extends('layouts.application-blank', ['title' => __('Account-instelling')])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <x-account.profile-information-banner :user=$user/>
            </div>
        </div>

        <div class="row py-4">
            <div class="col-3">
                @include('account.components.sidebar')
            </div>

            <div class="col-9">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <form method="POST" action="{{ route('user-password.update') }}" class="card bg-white border-0 shadow-sm">
                                @csrf {{-- Cross-site request forgery protection --}}
                                @method('PUT')

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h5 class="card-title fw-bold color-green">Wachtwoord aanpassen</h5>
                                            <h6 class="card-subtitle text-muted border-bottom pb-2">Zorg ervoor dat uw account een lang, willekeurig wachtwoord gebruikt om veilig te blijven</h6>
                                        </div>
                                        <div class="col-12">
                                            <label for="currentPassword" class="form-label">Huidig wachtwoord <span class="fw-bold text-danger">*</span></label>
                                            <input type="password" id="currentPassword" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" name="current_password">
                                            <x-forms.validation-error field="current_password" bag="updatePassword"/>
                                        </div>

                                        <div class="col-6">
                                            <label for="newPassword" class="form-label">Nieuw wachtwoord <span class="fw-bold text-danger">*</span></label>
                                            <input type="password" id="newPassword" class="form-control @error('password', 'updatePassword') is-invalid @enderror" name="password">
                                            <x-forms.validation-error field="password" bag="updatePassword"/>
                                        </div>

                                        <div class="col-6">
                                            <label for="passwordConfirmation" class="form-label">Wachtwoord bevestiging <span class="fw-bold text-danger">*</span></label>
                                            <input type="password" id="passwordConfirmation" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" name="password_confirmation">
                                            <x-forms.validation-error field="password_confirmation" bag="updatePassword"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer border-top-0 bg-light">
                                    <div class="float-end">
                                        <button type="reset" class="btn btn-sm btn-link">
                                            reset
                                        </button>

                                        <button type="submit" class="btn btn-sm btn-submit">
                                            <x-heroicon-o-pencil-square class="icon icons-sm me-1"/> aanpassen
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
