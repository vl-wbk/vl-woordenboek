@extends ('layouts.application-blank', ['title' => 'Nieuws', 'paddingContent' => 'pb-4 mb-5'])

@section('content')
    <div class="container">
        <div class="row justify-content-center py-4">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        <div class="float-start">
                            <h3 class="color-green mb-0">Deel je artikel</h3>
                        </div>
                    </div>

                    <div class="col-12 my-3 text-muted">
                        <p>
                            Dien je artikel in bij ons online nieuwsportaal.
                            We zijn op zoek naar hoogwaardige artikelen over een breed scala aan onderwerpen, van actueel nieuws tot diepgaande analyses en opinies.
                            Je artikelen moeten informatief, boeiend en goed onderbouwd zijn.
                            We behouden ons het recht voor om artikelen te weigeren die niet aan onze kwaliteitsnormen voldoen.
                        </p>

                        <p class="py-2">
                            Elk goedgekeurd artikel wordt in onze feed geplaatst.
                            Je kunt zoveel artikelen indienen als je wilt, en je kunt zelfs een artikel dat je al op je eigen blog hebt gepubliceerd, opnieuw indienen met de originele bronvermelding.
                        </p>

                        <p>
                            Nadat je een artikel hebt ingediend, wordt het beoordeeld voordat het wordt gepubliceerd. Afgewezen artikelen krijgen geen melding.
                            Nadat een artikel is gepubliceerd, kun je het niet meer bewerken, dus controleer het zorgvuldig voordat je het indient. Het herhaaldelijk indienen van hetzelfde artikel of het plaatsen van spam leidt tot een blokkering van je account.
                            We accepteren geen e-mailverzoeken voor gastartikelen.
                        </p>
                    </div>

                    <div class="col-12">
                        <form method="POST" action="{{ route('news:store') }}" class="card border-0 bg-white shadow-sm">
                            @csrf {{-- Form field protection --}}

                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert border-0 alert-danger alert-dismissible fade show" data-bs-dismiss="alert" role="alert">
                                        <span class="fw-bold">
                                            <x-heroicon-s-bell-alert class="icon me-1"/>
                                            We konden je artikel niet opslaan. Wegens de volgende validatie fouten in het formulier.
                                        </span>

                                        <hr class="my-2">

                                        <ul class="list-unstyled mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li><x-heroicon-o-exclamation-triangle class="icon me-1"/> {{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @elseif(flash()->message)
                                    <div class="alert {{ flash()->class }} border-0">
                                        <x-heroicon-o-check-circle class="icon mre-1"/> {{ flash()->message }}
                                    </div>
                                @endif

                                <div class="form-group row mb-3">
                                    <div class="col-9">
                                        <label for="title" class="form-label">Titel <span class="text-danger fw-bold">*</span></label>
                                        <input type="text" class="form-control" value="{{ old('titel') }}" name="titel" id="titel">
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="col-12">
                                        <label for="url" class="form-label">Originele url</label>
                                        <input type="text" class="form-control" value="{{ old('url') }}" name="url" aria-describedby="urlHelpText" id="url">
                                        <small id="urlHelpText" class="form-text text-muted">De URL naar je eigen blog of webstek.</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <label for="editor" class="form-label">Inhoud <span class="fw-bold text-danger">*</span></label>
                                        <textarea name="artikel" rows="3" id="editor" cols="30">{{ old('artikel') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer border-top-0 bg-light d-flex align-items-center justify-content-between">
                                <span class="text-danger">
                                    <x-heroicon-o-exclamation-circle class="icon me-1"/> U kunt geen artikel meer wijzigen indien het is ingestuurd ter publicatie.
                                </span>

                                <div class="float-end">
                                    <button type="submit" class="btn border-0 shadow-sm btn-submit">
                                        Insturen
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
@endsection

@section('scripts')
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

    <script>
        const form = document.querySelector('form');
        const easyMDE = new EasyMDE({
            minHeight: '100px',
            height: '200px',
            maxHeight: '250px',
            spellChecker: false,
            nativeSpellchecker: true,
            inputStyle: 'contenteditable',
            element: document.getElementById('editor')
        });
    </script>
@endsection