@extends('layouts.application-blank', ['title' => 'Inbox'])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="border-bottom pb-2 d-flex justify-content-between align-items-center">
                    <h3 class="text-success">
                        <a href="{{ route('profile:inbox') }}" class="text-muted text-decoration-none">
                            <x-heroicon-s-inbox class="icon me-1 icon-back-to-results"/>
                        </a>

                        Thread: {{ $thread->subject }}
                    </h3>

                    <a href="{{ route('profile:inbox') }}" class="btn btn-sm shadow-sm btn-light">
                        <x-heroicon-o-arrow-right-start-on-rectangle class="icon text-danger me-1"/> Terug naar inbox
                    </a>
                </div>
            </div>
        </div>

        <div class="row pt-3">
            <div class="col-md-9">
                <x-messages.flash-alert/>

                <div class="card shadow-sm bg-light border-0 rounded-3">
                    <div class="card-body p-4 overflow-auto d-flex flex-column-reverse" style="max-height: 500px;">
                        @foreach ($messages as $message)
                            <div class="d-flex align-items-center">
                                <img src="//www.gravatar.com/avatar/{{ md5($message->user->email) }}?s=64"
                                     alt="{{ $message->user->name }}" class="rounded-circle me-3" style="width: 48px; height: 48px;">

                                <div class="p-3 rounded-3 bg-white flex-grow-1">
                                    <h6 class="mb-0 text-success">{{ $message->user->name }} <small class="text-muted ms-2">{{ $message->created_at->diffForHumans() }}</small></h6>
                                    <p class="mb-0 text-dark">{{ $message->body }}</p>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <hr class="my-3">
                            @endif
                        @endforeach
                    </div>
                </div>

                <x-definitions.pagination :results=$messages />

                @can ('reply', $thread)
                    <hr>

                    <form action="{{ route('thread:reply', $thread) }}" method="post" class="card card-body bg-white border-0">
                        @csrf {{-- form field protection --}}

                        <div class="form-floating mb-3">
                            <textarea name="bericht" class="form-control @error('bericht') is-invalid @enderror rounded-3" id="message" style="height: 100px" placeholder="Typ je bericht hier...">{{ old('bericht') }}</textarea>
                            <label for="bericht">Typ je bericht hier...</label>
                            <x-forms.validation-error field="bericht"/>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success rounded-3">
                                <x-heroicon-o-paper-airplane class="icon me-2"/> Verstuur
                            </button>
                        </div>
                    </form>
                @endcan
            </div>

            <div class="col-md-3 mb-4">
                <h2 class="h5 text-muted border-bottom pb-2 d-flex justify-content-between align-items-center">
                    Deelnemers

                    @if ($thread->creator()->is(auth()->user()))
                        <span class="h6 color-green fw-bold">
                            <x-heroicon-o-user-group class="icon"/>
                            <span class="ms-1 small fst-italic">({{ $thread->participants->count() }}/10)</span>
                        </span>
                    @endif
                </h2>

                <ul class="list-unstyled text-muted @canany(['leave', 'add-participants'], $thread)  pb-2 border-bottom @endcanany">
                    @foreach ($thread->participants as $participant)
                        <li class="d-flex justify-content-between align-items-center">
                            <span>
                                <x-heroicon-s-user-circle class="icon color-green me-1"/>

                                <a href="{{ route('account:public', $participant->user) }}" class="text-dark text-decoration-none">
                                    {{ $participant->user->name }}
                                </a>
                            </span>

                            @can('remove-participants', [$thread, $participant])
                                <a href="{{ route('thread:leave', ['thread' => $thread, 'participant' => $participant]) }}" class="text-danger" title="Gebruiker verwijderen">
                                    <x-heroicon-o-user-minus class="icon"/>
                                </a>
                            @endcan
                        </li>
                    @endforeach

                    <li>

                    </li>
                </ul>

                @can ('leave', $thread)
                    <a href="{{ route('thread:leave', ['thread' => $thread, 'participant' => $userParticipantRecord]) }}" class="btn btn-outline-danger shadow-sm w-100">
                        <x-heroicon-o-arrow-right-start-on-rectangle class="icon me-1"/> verlaat deze conversatie
                    </a>
                @elsecan('add-participants', $thread)
                    <button type="button" data-bs-toggle="modal" data-bs-target="#addParticipant" class="btn btn-submit shadow-sm w-100">
                        <x-heroicon-o-user-plus class="icon me-1"/> persoon toevoegen
                    </button>
                @endcan
            </div>
        </div>
    </div>

    @can('add-participants', $thread)
        <div class="modal fade" id="addParticipant" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addParticipantLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0 modal-footer-reporting">
                        <div>
                            <h5 class="modal-title fw-bold color-green" id="exampleModalLabel">Iemand aan de thread toevoegen</h5>
                            <p class="mb-0 fs-6 fst-italic text-muted">Kies een persoon om mee te laten lezen en reageren.</p>
                        </div>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted pb-2 border-bottom fw-lighter">
                            Iemand betrekken in de conversatie? Geen probleem! Dat is mogelijk doormiddel van de <strong>exacte gebruikersnaam</strong> in te vullen in het onderstaande veld.
                        </p>

                        <form method="POST" id="storeParticipant" action="{{ route('thread:add-participant', $thread) }}">
                            @csrf {{-- Form field protection --}}

                            <div class="form-group row">
                                <div class="col-12 mt-3">
                                    <input type="text" class="form-control" placeholder="Exacte gebruikersnaam" name="gebruikersnaam">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer border-top-0 modal-footer-reporting">
                        <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="modal">
                            <x-heroicon-o-x-circle class="icon text-danger me-1"/> annuleren
                        </button>

                        <button type="submit" form="storeParticipant" class="btn btn-submit">
                            <x-heroicon-s-user-plus class="icon me-1"/> toevoegen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection