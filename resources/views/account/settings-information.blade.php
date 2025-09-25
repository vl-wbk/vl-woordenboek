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

                                    <div class="col-4">
                                        <label for="username" class="form-label">{{ __('Gebruikersnaam') }} <span class="fw-bold text-danger">*</span></label>
                                        <input name="gebruikersnaam" type="text" class="form-control @error('gebruikersnaam', 'updateProfileInformation') is-invalid @enderror" value="{{ old('gebruikersnaam', $user->name) }}" id="emailAddress">
                                        <x-forms.validation-error field="gebruikersnaam" bag="updateProfileInformation" />
                                    </div>

                                    <div class="col-md-4">
                                        <label for="firstname" class="form-label">Voornaam</label>
                                        <input name="firstname" type="text" class="form-control" value="{{ old('firstname', $user->firstname) }}" id="firstname">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="lastname" class="form-label">Achternaam</label>
                                        <input name="lastname" type="text" class="form-control" value="{{ old('lastname', $user->lastname) }}" id="lastname">
                                    </div>

                                    <div class="col-12">
                                        <label for="emailAddress" class="form-label">E-mailadres <span class="fw-bold text-danger">*</span></label>
                                        <input name="email" type="email" class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror" value="{{ old('email', $user->email) }}" id="emailAddress">
                                        <x-forms.validation-error field="email" bag="updateProfileInformation" />
                                    </div>

                                    <div class="col-12">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea name="bio" aria-describedby="bioHelp" rows="4" class="form-control @error('bio', 'updateProfileInformation') is-invalid @enderror" placeholder="Vertel eens kort iets over jezelf">{{ old('bio', $user->bio) }}</textarea>

                                        @if ($errors->updateProfileInformation->has('bio'))
                                            <x-forms.validation-error field="bio" bag="updateProfileInformation" />
                                        @else
                                            <div id="bioHelp" class="form-text">Je bio kan maar maximaal <strong>160 tekens</strong> bevatten.</div>
                                        @endif
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
                                    <h5 class="card-title fw-bold color-green">Sociale koppelingen</h5>
                                    <h6 class="card-subtitle text-muted border-bottom pb-2">
                                        Wil jij dat je social media accounts of je website zichtbaar is op je openbaar profiel?
                                        Dat kan doormiddel van het onderstaande formulier.
                                    </h6>

                                    <form action="{{ route('profile.settings.social-references')  }}" method="POST" id="socialMediaReferences">
                                        @csrf {{-- Form field protetion --}}
                                        @method('PATCH') {{-- HTTP method spoofing --}}

                                        <div class="row g-3 mt-1">
                                            <div class="col-sm-4">
                                                <label class="visually-hidden" for="blueskyUsername">Bluesky gebruikersnaam</label>
                                                <div class="input-group">
                                                    <div class="input-group-text bg-dark text-yellow">
                                                        <x-tabler-brand-bluesky class="icon"/>
                                                    </div>

                                                    <input type="text" class="form-control" name="bluesky" value="{{ old('bluesky', $user->bluesky) }}" id="specificSizeInputGroupUsername" aria-describedby="blueskyHelpBlock" placeholder="gebruikersnaam">

                                                    <div id="blueskyHelpBlock" class="form-text">
                                                        Voer uw Bluesky-naam in zonder het @-symbool ervoor
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="visually-hidden" for="specificSizeInputGroupUsername">Twitter gebruikersnaam</label>
                                                <div class="input-group">
                                                    <div class="input-group-text bg-dark text-yellow">
                                                        <x-tabler-brand-x class="icon"/>
                                                    </div>

                                                    <input type="text" name="twitter" value="{{ old('twitter', $user->twitter) }}" class="form-control" id="specificSizeInputGroupUsername" aria-describedby="twitterHelpBlock" placeholder="gebruikersnaam">

                                                    <div id="twitterHelpText" class="form-text">
                                                        Voer uw X (Twitter)-handle in zonder het voorafgaande @-symbool
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="visually-hidden" for="specificSizeInputGroupUsername">Website</label>
                                                <div class="input-group">
                                                    <div class="input-group-text bg-dark text-yellow">
                                                        <x-heroicon-s-globe-europe-africa class="icon"/>
                                                    </div>

                                                    <input type="text" name="website" value="{{ old('website', $user->website) }}" class="form-control @error('website') is-invalid @enderror" id="specificSizeInputGroupUsername" placeholder="website">
                                                    <x-forms.validation-error field="website"/>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="bg-light card-footer border-top-0">
                                    <div class="float-end">
                                        <button type="reset" class="btn btn-sm btn-link">
                                            reset
                                        </button>

                                        <button type="submit" form="socialMediaReferences" class="btn btn-sm btn-submit">
                                            <x-heroicon-o-pencil-square class="icon me-1"/> aanpassen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

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
