@extends('layouts.application-blank', ['title' => 'Inbox'])

@section('content')
    <div class="my-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                        <h3 class="color-green">Berichtencentrum - inbox</h3>

                        <form action="{{ request()->fullUrl() }}" class="w-25">
                            <div class="d-flex align-items-center">
                                <input type="text" name="onderwerp" value="{{ request()->get('onderwerp') }}" class="form-control form-control-sm shadow-sm me-2 flex-grow-1 w-50" placeholder="Bericht zoeken op basis van onderwerp">
                                <button type="submit" class="btn btn-sm btn-submit border-0">
                                    <x-heroicon-s-magnifying-glass class="icon icon-sm"/>
                                </button>
                            </div>
                        </form>
                    </div>
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

                    @if ($threads->count() > 0)
                        <ul class="list-group shadow-sm">
                            @foreach ($threads as $thread)
                                <a href="{{ route('inbox:show', $thread) }}" class="list-group-item border-0 @if (! $loop->last) border-bottom @endif list-group-item-action {{ $thread->isUnread(Auth::id()) ? 'list-group-item-info' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $thread->subject }}</h5>


                                        @if ($thread->isUnread(Auth::id()))
                                            <small><strong>{{ $thread->userUnreadMessagesCount(Auth::id()) }} nieuwe berichten</strong></small>
                                        @endif
                                    </div>

                                    <p class="mb-1">
                                        Verzender: <strong>{{ $thread->creator()->name }}</strong>
                                    </p>
                                </a>
                            @endforeach
                        </ul>

                        <x-definitions.pagination :results=$threads />
                    @else {{-- The user has no active threads --}}
                    <div class="card bg-sidenav text-center border-0">
                        <div class="card-body p-4">
                            <x-heroicon-o-inbox class="icon-blankslate color-green icon pb-3"/>
                            <h5 class="card-title fw-bold">Geen berichten gevonden</h5>

                            <p class="card-text text-muted">
                                Er zijn geen berichten gevonden in de huidige inbox. Het lijkt erop of je nog geen berichten hebt ontvangen of verzonden. Of dat je alle ongelezen berichten hebt gelezen.
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
