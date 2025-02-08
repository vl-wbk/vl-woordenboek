<x-layouts.application>
    <div class="card bg-white border-0 shadow-sm">
        <div class="card-header border-bottom-0">
            Definitie van een woord insturen
        </div>
        <form action="" method="POST" class="card-body">
            <p class="card-text mb-0">
                Elke gebruiker kan nieuwe termen en hun definities aanleveren bij het Vlaams Woordenboek. <br>
                Met het volgende formulier kunt ge nieuwe beschrijvingen van typisch vlaamse woorden, termen en uitspraken toevoegen aan onze databank.
            </p>

            <hr class="my-2">

            <div class="form-group">
                <label for="woord" class="col-form-label">Woord <span class="fw-bold text-danger">*</span></label>
                <input type="text" name="woord" id="woordHelptext" class="form-control">
                <small id="woordHelpText" class="form-text text-muted">
                    <x-tabler-info-circle class="icon icon-sm me-1"/> Het woord, term of de uitspraak die hier wordt gedefinieerd. Gebruik liefst geen hoofdletters.
                </small>
            </div>

            <div class="form-group">
                <label for="kenmerken" class="col-form-label">Kenmerken</label>
                <input type="text" name="kenmerken" id="kenmerkenHelpText" class="form-control">
                <span id="kenmerkenHelpText" class="form-text text-muted">
                    <x-tabler-info-circle class="icon icon-sm me-1"/> Je kunt hier woordkenmerken aangeven, zoals het lidwoord, geslacht en meervoud van een zelfstandig naamwoord. Bijvoorbeeld: de ~ (v.), ~sen.
                </span>
            </div>

            <div class="form-group">
                <label for="beschrijving" class="col-form-label">Beschrijving <span class="text-danger fw-bold">*</span></label>
                <textarea name="beschrijving" class="form-control" id="beschrijvingHelpText" cols="4"></textarea>
                <span id="beschrijvingHelpText" class="form-text text-muted">
                    <x-tabler-info-circle class="icon icon-sm me-1"/> Beschrijf de term in Algemeen Beschaafd Vlaams en beperk je tot één betekenis per beschrijving. Voeg andere betekenissen apart toe.
                </span>
            </div>

            <div class="form-group">
                <label for="regio" class="col-form-label">Regio <span class="text-danger fw-bold">*</span></label>
                <select id="regioHelpText" class="form-control" name="regio[]" multiple size="10">
                    @foreach ($regions as $region => $value)
                        <option value="{{ $region }}">{{ $value }}</option>
                    @endforeach
                </select>

                <span id="regioHelptext" class="form-text text-muted">
                    <x-tabler-info-circle class="icon icon-sm me-1"/> Indien het woord enkel in een lokaal dialect wordt gebruikt, geef dan ook de streek aan waar deze term wordt gebruikt.
                    - <a href="" target="_blank">Meer info over de regio's.</a> <br>

                    U kunt meerdere regio's aanklikken doormiddel van de CRTL toets in te drukken.
                </span>
            </div>

            <hr class="mb-1">

            <div class="form-group">
                <label for="voorbeeld" class="col-form-label">Voorbeeld <span class="fw-bold text-danger">*</span></label>
                <textarea name="voorbeeld" id="voorbeeldHelpText" class="form-control" cols="6"></textarea>

                <span id="voorbeeldHelptext" class="form-text text-muted">
                    <x-tabler-info-circle class="icon icon-sm me-1"/> Geef een voorbeeldzin in Algemeen Beschaafd Vlaams die de hierboven beschreven betekenis van het woord verduidelijkt.
                </span>
            </div>
        </form>

        <div class="card-footer">
            <button type="submit" class="btn btn-sm btn-suggestion-submit">
                <x-tabler-send class="icon icon-sm me-1" />Insturen
            </button>
            <button type="rest" class="btn btn-reset btn-sm">
                <x-tabler-arrow-back-up class="icon icon-sm me-1 text-danger"/> Reset
            </button>
        </div>
    </div>
</x-layouts.application>
