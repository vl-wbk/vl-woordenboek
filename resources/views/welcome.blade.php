
<x-layouts.application>
    <div class="row">
        <div class="col-12">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header border-bottom-0">Zoeken</div>

                    <div class="card-body">
                        <form>
                            <input type="email" class="form-control mb-3" id="exampleFormControlInput1" placeholder="name@example.com">
                                <button class="btn btn-search">Zoeken</button>
                                <button class="btn btn-link">reset</button>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info alert-important shadow-sm" role="alert">
                        <h4 class="alert-heading">Geen woorden gevonden!</h4>

                        <p>
                            Het lijkt erop dat u geen zoekterm hebt opgegeven en/of er woorden zijn gevonden zijn met uw zoekterm.
                            Indien het woord dat u zoekt een vlaams woord is en niet is opgenomen in ons woordenboek. Kunt u via de knop hieronder
                            een suggestie voor het toevoegen van het woord in kwestie aan ons woordenboek.
                        </p>

                        <hr>

                        <a href="{{ route('definitions.create') }}" class="btn btn-info btn-sm text-white">
                            Suggestie toevoegen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.application>
