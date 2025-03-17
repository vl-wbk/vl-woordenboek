@extends('layouts.application-blank', ['title' => __('Account-instelling')])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <x-account.profile-information-banner :user=$user/>
            </div>
        </div>

        {{-- User profile settings form --}}
        <div class="row py-4">
            <div class="col-4">
                <h5 class="fw-bold color-green">Account informatie</h5>
                <p class="text-muted mb-0">Update de profielgegevens en het e-mailadres van uw account.</p>
            </div>


            <div class="col-8">
                <form method="POST" action="{{ route('user-profile-information.update') }}" class="card bg-white border-0 shadow-sm">
                    @csrf {{-- Cross-site request forgery protection --}}
                    @method('PUT')

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="firstname" class="form-label">Voornaam <span class="fw-bold text-danger">*</span></label>
                                <input name="firstname" type="text" class="form-control @error('firstname', 'updateProfileInformation') is-invalid @enderror" value="{{ old('firstname', $user->firstname) }}" id="firstname">
                                <x-forms.validation-error field="firstname" bag="updateProfileInformation" />
                            </div>

                            <div class="col-md-7">
                                <label for="lastname" class="form-label">Achternaam <span class="fw-bold text-danger">*</span></label>
                                <input name="lastname" type="text" class="form-control @error('lastname', 'updateProfileInformation') is-invalid @enderror" value="{{ old('lastname', $user->lastname) }}" id="lastname">
                                <x-forms.validation-error field="lastname" bag="updateProfileInformation" />
                            </div>

                            <div class="col-12">
                                <label for="emailAddress" class="form-label">E-mail adres <span class="fw-bold text-danger">*</span></label>
                                <input name="email" type="email" class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror" value="{{ old('email', $user->email) }}" id="emailAddress">
                                <x-forms.validation-error field="email" bag="updateProfileInformation" />
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-sm btn-submit">
                            <x-heroicon-o-pencil-square class="icon icon-sm me-1"/> aanpassen
                        </button>

                        <button type="reset" class="btn btn-sm btn-link">
                            reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <hr class=" my-0 text-body-tertiary">

        <div class="row py-4">
            <div class="col-4">
                <h5 class="fw-bold color-green">Wachtwoord aanpassen</h5>
                <p class="text-muted mb-0">Zorg ervoor dat uw account een lang, willekeurig wachtwoord gebruikt om veilig te blijven</p>
            </div>

            <div class="col-8">
                <form method="POST" action="{{ route('user-password.update') }}" class="card bg-white border-0 shadow-sm">
                    @csrf {{-- Cross-site request forgery protection --}}
                    @method('PUT')

                    <div class="card-body">
                        <div class="row g-3">
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

                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-sm btn-submit">
                            <x-heroicon-o-pencil-square class="icon icons-sm me-1"/> aanpassen
                        </button>

                        <button type="reset" class="btn btn-sm btn-link">
                            reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <hr class=" my-0 text-body-tertiary">

        <div class="row py-4">
            <div class="col-4">
                <h5 class="fw-bold color-green">Account verwijderen</h5>
                <p class="text-muted mb-0">Verwijder uw account permanent</p>
            </div>
            <div class="col-8">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header fw-bold bg-danger text-bg-danger">
                        <x-heroicon-s-exclamation-triangle class="icon me-1"/> Opgepast
                    </div>

                    <div class="card-body">
                        <p class="card-text lh-sm text-muted">
                            Het verwijderen van je account is permanent. Al je bijdragen, bewerkingen en voorkeuren worden gewist, maar openbare wijzigingen blijven zichtbaar met een anonieme auteur.
                            Indien u zeker bent van de verwijdering van je account vragen we enkel nog je huidig wachtwoord in te geven in het onderstaande formulier.
                        </p>

                        <hr>

                        <form action="{{ route('account.delete') }}" id="accountDeletion" method="POST">
                            @csrf
                            <input type="password" name="password" placeholder="Uw huidig wachtwoord" class="form-control @error('password') is-invalid @enderror"/>
                            <x-forms.validation-error field="password"/>
                        </form>
                    </div>

                    <div class="bg-white card-footer">
                        <button type="submit" form="accountDeletion" class="btn btn-sm btn-danger">
                            <x-heroicon-s-trash class="icon icon-sm me-1"/> Account verwijderen
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- END - user profile settings form --}}
    </div>
@endsection
