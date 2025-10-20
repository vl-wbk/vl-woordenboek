@extends('layouts.application-blank', ['title' => 'Contacten'])

@section('content')
    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1 class="color-green h3 border-bottom pb-2">Berichtencentrum - contacten</h1>
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-md-4">
                    <div class="d-flex justify-content-start gap-2">
                        <a href="{{ route('contacts:create') }}" class="btn btn-light border-0 shadow-sm w-50">
                            <x-heroicon-o-plus class="icon me-1"/> contact toevoegen
                        </a>

                        <a href="{{ route('inbox:create') }}" class="btn btn-submit border-0 shadow-sm w-50">
                            <x-heroicon-o-envelope-open class="icon me-1"/> nieuw bericht
                        </a>
                    </div>

                    <hr>

                    <div class="list-group border-0 shadow-sm">
                        <a href="{{ route('profile:inbox') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                            <div class="me-auto">
                                <x-heroicon-s-inbox class="icon me-2 color-green"/> Ongelezen berichten
                            </div>

                            @if(auth()->user()->unreadMessagesCount() > 0)
                                <span class="badge badge-gray rounded-pill">
                                {{ auth()->user()->unreadMessagesCount() }}
                            </span>
                            @endif
                        </a>

                        <a href="{{ route('profile:inbox', ['type' => \App\Enums\Inbox::All]) }}" class="list-group-item list-group-item-action">
                            <x-heroicon-s-inbox class="icon me-2 color-green"/> Alle berichten
                        </a>
                    </div>

                    <hr>

                    <div class="list-group border-0 shadow-sm">
                        <a href="{{ route('contacts:index') }}" class="list-group-item list-group-item-action">
                            <x-heroicon-s-queue-list class="icon me-2 color-green"/>Contacten
                        </a>
                    </div>
                </div>

                <div class="col-md-8">
                    <x-messages.flash-alert/>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h2 class="mb-0 h5">Contactpersoon toevoegen</h2>
                        </div>

                        <div class="card-body bg-white">
                            <p class="card-text text-muted mb-2">
                                Voeg eenvoudig een nieuwe contactpersoon toe aan je berichtencentrum.
                                Voer de <span class="fw-bold">exacte gebruikersnaam</span> in van de persoon die je wilt toevoegen.
                            </p>

                            <p class="card-text text-muted">
                                Nadat je de gebruikersnaam hebt ingevuld, klik je op de knop <span class="fw-bold">'Toevoegen'</span>.
                                De gebruiker wordt dan aan je contactlijst toegevoegd, waarna je direct berichten kunt uitwisselen.
                                Je contactenlijst is alleen zichtbaar voor jou.
                            </p>

                            <hr>

                            <form action="{{ route('contacts:store') }}" method="POST" id="addContact">
                                @csrf {{-- Form field protection --}}

                                <div class="form-group row">
                                    <div class="col-12">
                                        <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" value="{{ old('gebruikersnaam') }}" class="form-control @error('gebruikersnaam') is-invalid @enderror"/>
                                        <x-forms.validation-error field="gebruikersnaam"/>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-footer border-top-0 bg-light">
                            <button type="submit" form="addContact" class="btn btn-submit btn-sm">
                                <x-heroicon-s-user-plus class="icon me-1"/> Toevoegen
                            </button>

                            <a href="{{ route('contacts:index') }}" class="btn btn-sm text-decoration-none btn-link">
                                <x-heroicon-o-x-circle class="icon me-1 text-danger"/> annuleren
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
