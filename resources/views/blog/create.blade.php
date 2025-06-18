@extends('layouts.application-blank', ['title' => trans('Artikel toevoegen')])

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="float-start">
                    <h3 class="color-green">Schrijf een nieuw artikel</h3>
                </div>

                <div class="float-end">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar met knoppen met relatie op de artikel inzending">
                        <div class="btn-group border-0 shadow-sm me-2" role="group" aria-label="First group">
                            <a href="{{  route('news:index') }}" class="btn btn-light">
                                <x-heroicon-o-x-mark class="icon me-1 text-danger"/>Annuleren
                            </a>
                            <a href="" class="btn btn-light">
                                <x-heroicon-s-queue-list class="icon mr-1 color-green"/> Mijn artikelen

                                @if ($writtenArticles > 0)
                                    <span class="badge text-bg-secondary">{{ $writtenArticles }}</span>
                                @endif
                            </a>
                        </div>

                        <div class="btn-group border-0 shadow-sm" role="group" aria-label="Second group">
                            <button type="submit" form="createArticleForm" class="btn btn-submit">
                                <x-heroicon-o-paper-airplane class="icon me-1"/>Insturen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-12">
                <p>
                    Heb jij een intressante insteek over de nederlands taal en hun gerleateerde onderwerpen en wil je iets delen met ons publiek?
                    Via dit onderstaande formulier kan je het delen met de gebruikers van het Vlaams Woordenboek. <strong>De artikelen mogen niet van promotionele aard zijn</strong>
                    en moeten van educatieve of informatieve aard zijn. We behouden ons het recht om artikelen die afwijken van deze norm te verwijderen en niet te publiceren.
                </p>

                <p class="my-2">Elk artikel zal worden publiceerd met onze gebruiker.</p>

                <p>
                    Nadat artikelen ter goedkeuring zijn ingediend, worden ze beoordeeld voordat ze worden gepubliceerd.
                    Er wordt geen melding verzonden van afgewezen artikelen. In plaats daarvan raden we aan om artikelen ook op je eigen kanaal te crossposten.
                    Na publicatie kun je je artikel niet meer bewerken, dus controleer het grondig voordat je het ter goedkeuring indient.
                    Het twee keer indienen van hetzelfde artikel of het plaatsen van spam leidt tot een blokkering van je account. We accepteren geen e-mailverzoeken voor gastartikelen.
                    Een dergelijk verzoek wordt genegeerd.
                </p>
            </div>

            <div class="col-12">
                <form method="POST" id="createArticleForm" action="{{ route('news:store') }}" class="card card-body border-0 shadow-sm bg-white">
                    @csrf {{-- Form protection --}}

                    <div class="row">
                        <div class="form-group mb-3">
                            <div class="col-8">
                                <label for="title" class="form-label">Titel <span class="text-danger fw-bold">*</span></label>
                                <input type="text" class="form-control" name="titel" id="titel"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <label for="categories" class="form-label">Categorieen</label>
                            <select name="categorieen[]" id="categories" class="form-select" aria-describedby="categoriesHelpBlock" size="9" multiple>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('regio', [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div id="categoriesHelpBlock" class="form-text">
                                <x-heroicon-o-information-circle class="icon icon-sm me-1"/>Wil je meerdere categorieen aanklikken? Hou de CTRL-toets ingedrukt terwijl je een voor een op de categorieen klikt.
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="content" class="form-label">Uw artikel <span class="fw-bold text-danger">*</span></label>
                            <textarea name="conetent" id="content" class="form-control" rows="10" aria-describedby="contentHelpBlock"></textarea>

                            <div id="contentHelpBlock" class="form-text">
                                <x-heroicon-o-information-circle class="icon icon-sm me-1"/>Dit veld is <a href="https://www.markdownguide.org/basic-syntax/" target="_blank">markdown</a> ondersteund voor de opmaak van het artikel
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
