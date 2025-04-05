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

                        <hr class="my-3 text-body-tertiary">

                        <div class="card bg-white border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-bold color-green">Aangemelde apparaten</h5>
                                <h6 class="card-subtitle text-muted">Beheer en log uit uw actieve sessies op andere browsers en apparaten</h6>

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show border-0 d-flex align-items-center mt-2 py-1 px-2 mb-0" role="alert" data-bs-dismiss="alert" aria-label="Close">
                                        <x-heroicon-o-bell-alert class="flex-shrink-0 me-1 icon"/>
                                        <div>Wij konden helaas niet verifieren dat je de accounthouder bent en hebben daarom de handeling vroegtijdig afgebroken.</div>
                                    </div>
                                @endif
                            </div>

                            <div class="list-group border-top list-group-flush">
                                @foreach ($sessions as $session)
                                    <div class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="me-auto">
                                            <div class="fw-bold">{{ $session->agent->platform() ? $session->agent->platform() : __('Onbekend') }} - {{ $session->agent->browser() ? $session->agent->browser() : __('Onbekend') }}</div>

                                            <ul class="list-inline mb-0 mt-1 text-muted">
                                                <li class="list-inline-item">
                                                    @if ($session->agent->isDesktop())
                                                        <x-heroicon-s-computer-desktop class="icon me-1"/> PC
                                                    @else
                                                        <x-heroicon-s-device-phone-mobile class="icon me-1"/> Mobiel apparaat
                                                    @endif
                                                </li>

                                                <li class="list-inline-item"><x-heroicon-s-globe-europe-africa class="icon me-1"/> {{ $session->ip_address }}</li>
                                                <li class="list-inline-item"><x-heroicon-s-clock class="icon me-1"/> Laatste activiteit: {{ $session->last_active }}</li>
                                            </ul>
                                        </div>

                                        @if ($session->is_current_device)
                                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill">huidig apparaat</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="card-footer bg-light border-top">
                                <button type="button" data-bs-toggle="modal" class="btn btn-submit btn-sm" data-bs-target="#logoutOtherDevicesModal">
                                    <x-heroicon-o-arrow-left-start-on-rectangle class="icon me-1"/> andere browsersessies afmelden
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Password confirmation modal --}}
    <div class="modal fade" id="logoutOtherDevicesModal" tabindex="-1" aria-labelledby="logoutOtherDevicesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger border-bottom-0 text-danger-emphasis">
                    <h1 class="modal-title text-white fs-5" id="logoutOtherDevicesModalLabel">Wachtwoord bevestigen</h1>
                </div>

                <div class="modal-body">
                    <p class="card-text lh-sm text-muted pb-3 border-bottom">
                        Om zeker te weten dat u het bent, vragen we u vriendelijk om uw wachtwoord in te voeren.
                    </p>

                    <form action="{{ route('profile.delete-browser-sessions') }}" method="POST" id="passwordConfirmationForm" class="pt-3">
                        @csrf {{-- Form field protection --}}
                        @method('DELETE') {{-- HTTP method spoofing --}}

                        <label for="currentPassword" class="visually-hidden">Uw huidige wachtwoord</label>
                        <input type="password" id="currentPassword" class="form-control" name="password" placeholder="Uw huidig wachtwoord"/>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">annuleren</button>
                    <button type="submit" class="btn btn-outline-success" form="passwordConfirmationForm">
                        <x-tabler-send class="icon me-1"/> bevestigen
                    </button>
                </div>
            </div>
        </div>
    </div> {{-- END password wonfirmation box --}}
@endsection
