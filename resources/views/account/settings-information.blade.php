@extends('layouts.application-blank', ['title' => __('Account-instelling')])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <x-account.profile-information-banner :user=$user/>
            </div>
        </div>

        <div class="row py-4">
            <div class="col-lg-3 col-sm-12">
                @include('account.components.sidebar')
            </div>

            <div class="col-lg-9 col-sm-12">
                <div class="row">
                    <div class="col-12">
                        <form method="POST" action="{{ route('user-profile-information.update') }}" class="card bg-white border-0 shadow-sm">
                            @csrf {{-- Cross-site request forgery protection --}}
                            @method('PUT')

                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h5 class="card-title fw-bold color-green">Accountinformatie</h5>
                                        <h6 class="card-subtitle text-muted border-bottom pb-2">Werk je profielgegevens en het e-mailadres van je account bij.</h6>
                                    </div>

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
                                        <label for="emailAddress" class="form-label">E-mailadres <span class="fw-bold text-danger">*</span></label>
                                        <input name="email" type="email" class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror" value="{{ old('email', $user->email) }}" id="emailAddress">
                                        <x-forms.validation-error field="email" bag="updateProfileInformation" />
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer border-0 bg-light">
                                <div class="float-end">
                                    <button type="reset" class="btn btn-sm btn-link">
                                        reset
                                    </button>

                                    <button type="submit" class="btn btn-sm btn-submit">
                                        <x-heroicon-o-pencil-square class="icon me-1"/> aanpassen
                                    </button>
                                </div>
                            </div>
                        </form>

                        <hr class="my-3 text-body-tertiary">

                        <div class="col-12">
                            <div class="card bg-white border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold text-danger">Account verwijderen</h5>
                                    <h6 class="card-subtitle text-muted border-bottom pb-2">Liever geen account meer bij het Vlaams woordenboek? Hou dan rekening met deze gevolgen.</h6>

                                    <p class="card-text lh-sm text-muted my-3">
                                        Het verwijderen van je account is permanent. Al je bijdragen, bewerkingen en voorkeuren worden gewist, maar openbare wijzigingen blijven zichtbaar met een anonieme auteur.
                                        Als je er zeker van bent dat je je account definitief wil verwijderen, typ dan je huidige wachtwoord in het veld hieronder en klik op ‘Account verwijderen’.
                                    </p>

                                    <form action="{{ route('account.delete') }}" id="accountDeletion" method="POST">
                                        @csrf
                                        <input type="password" name="password" placeholder="Je huidige wachtwoord" class="form-control @error('password') is-invalid @enderror"/>
                                        <x-forms.validation-error field="password"/>
                                    </form>
                                </div>

                                <div class="bg-light card-footer border-top-0">
                                    <button type="submit" form="accountDeletion" class="btn btn-sm btn-danger float-end">
                                        <x-heroicon-s-trash class="icon icon-sm me-1"/> Account verwijderen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
