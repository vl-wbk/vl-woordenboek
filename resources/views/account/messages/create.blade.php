@extends('layouts.application-blank', ['title' => 'Inbox'])

@section('content')
    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h3 class="color-green border-bottom pb-2">Berichtencentrum - nieuw bericht</h3>
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

                    <div class="card border-0 bg-white shadow-sm">
                        <form method="POST" action="{{ route('inbox:store') }}" id="createMessage" class="card-body">
                            @csrf {{-- form field protection --}}

                            <div class="form-group row mb-3">
                                <div class="col-4">
                                    <label for="receiver" class="form-label">Ontvanger <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" name="ontvanger" placeholder="Ontvanger van uw bericht" class="form-control @error('ontvanger') is-invalid @enderror" value="{{ old('ontvanger', optional($reciever)->name) }}" @if(! $reciever) autofocus @endif>
                                    <x-forms.validation-error field="ontvanger"/>
                                </div>

                                <div class="col-md-8">
                                    <label for="subject" class="form-label">Onderwerp <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" name="onderwerp" placeholder="Het onderwerp van uw bericht" class="form-control @error('onderwerp') is-invalid @enderror" value="{{ old('onderwerp') }}"  @if($reciever) autofocus @endif>
                                    <x-forms.validation-error field="onderwerp"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="message" class="form-label">Uw bericht <span class="text-danger fw-bold">*</span></label>
                                    <textarea name="bericht" rows="6" placeholder="uw bericht..." class="form-control @error('bericht') is-invalid @enderror">{{ old('bericht') }}</textarea>

                                    <x-forms.validation-error field="bericht"/>
                                </div>
                            </div>
                        </form>

                        <div class="card-footer bg-light-subtle border-0">
                            <button type="submit" form="createMessage" class="btn btn-sm btn-submit" >
                                <x-heroicon-o-paper-airplane class="icon me-1"/> verzenden
                            </button>

                            <button type="reset" form="createMëssage" class="btn text-decoration-none btn-sm btn-link">
                                <x-heroicon-o-arrow-path class="icon me-1 text-danger"/> reset
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
