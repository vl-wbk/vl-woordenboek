@extends('layouts.application-blank', ['title' => 'Contacten'])

@section('content')
    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h3 class="color-green border-bottom pb-2">Berichtencentrum - mijn contacten</h3>
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

                    @if ($contacts->total() > 0 || ($contacts->total() === 0 && request()->has('zoekterm')))
                        <div class="card border-0 bg-white shadow-sm">
                            <div class="card-header bg-light-subtle">
                                <form action="">
                                    <div class="form-group row g-2">
                                        <div class="col-10">
                                            <input type="text" name="zoekterm" value="{{ request()->get('zoekterm') }}" placeholder="Zoek persoon op basis van zijn gebruikersnaam" class="form-control bg-white">
                                        </div>
                                        <div class="col-2">
                                            <button type="submit" class="btn btn-submit w-100">
                                                <x-heroicon-o-magnifying-glass class="icon me-1"/>Zoeken
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                @if($contacts->total() === 0 && request()->filled('zoekterm'))
                                    <div class="card bg-sidenav text-center border-0">
                                        <div class="card-body p-4">
                                            <x-heroicon-o-queue-list class="icon-blankslate color-green icon pb-3"/>
                                            <h5 class="card-title fw-bold">Geen contacten gevonden</h5>

                                            <p class="card-text text-muted">
                                                Het lijkt erop dat er geen contacten zijn gevonden met de opgegeven zoekterm.<br>
                                                Verwijder de zoekterm of probeer het opnieuw.
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="border-top-0 text-muted">Gebruiker</th>
                                                <th scope="col" class="border-top-0 text-muted">Laatst online</th>
                                                <th scope="col" class="border-top-0 text-muted" colspan="2">Toegevoegd op</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($contacts as $contact)
                                                <tr>
                                                    <th class="color-green" scope="row">
                                                        <x-heroicon-o-user-circle class="icon me-1"/> {{ $contact->name }}
                                                    </th>

                                                    <td>{{ optional($contact->last_seen_at)->diffForHumans() ?? '-' }}</td>
                                                    <td>{{ optional($contact->pivot->created_at)->format('d/m/Y') ?? '-' }}</td>

                                                    <td>
                                                        <span class="float-end">
                                                            <a href="{{ route('account:public', $contact) }}" class="text-muted text-decoration-none" title="Bericht gebruiker">
                                                                <x-heroicon-o-eye class="icon"/>
                                                            </a>

                                                            <span class="vr mx-1"></span>

                                                            <a href="{{ route('inbox:create', ['participant' => $contact->id]) }}" class="text-muted text-decoration-none" title="Bericht gebruiker">
                                                                <x-heroicon-o-envelope class="icon"/>
                                                            </a>

                                                            <span class="vr mx-1"></span>

                                                            <a href="{{ route('contacts:delete', $contact) }}" class="text-danger text-decoration-none" title="Verwijder contact">
                                                                <x-heroicon-o-trash class="icon"/>
                                                            </a>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <x-definitions.pagination :results=$contacts />
                    @elseif($contacts->total() === 0) {{-- There is no search param present and the user has no saved contacts --}}
                    <div class="card bg-sidenav text-center border-0">
                        <div class="card-body p-4">
                            <x-heroicon-o-queue-list class="icon-blankslate color-green icon pb-3"/>
                            <h5 class="card-title fw-bold">Geen contacten toegevoegd</h5>

                            <p class="card-text text-muted">
                                Het lijkt erop dat je nog geen contactpersonen hebt bewaard in je berichtencentrum. Je kan een contact toevoegen doormiddel van de knop links boven.
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
