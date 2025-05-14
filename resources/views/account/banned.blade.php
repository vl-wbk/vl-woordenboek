@extends ('layouts.server-error-page', ['title' => '403 - Geen toegang'])

@section ('content')
<div class="container">
    <div class="hero text-center my-4">
        <h1 class="display-5"><i class="bi bi-exclamation-diamond text-danger mx-3"></i></h1>
        <h1 class="display-5 fw-bold">Geen toegang</h1>
        <p class="lead">Met dit account heb je geen toegang tot de webpagina op <em><span id="display-domain"></span></em>.</p>
    </div>

    <div class="content">
        <div class="row  justify-content-center py-3">
            <div class="col-md-7">
                <div class="my-5 p-3 card">
                    <div class="card-body">
                        <h3>Wat is er gebeurd?</h3>
                        <p class="">
                            Een beheerder heeft gemerkt dat er vanaf dit account inbreuken gebeurd zijn op de algemene voorwaarden of dat er ongewone activiteiten, zoals spamming, hebben plaatsgevonden. Daarom is je account gedeactiveerd.
                        </p>

                        <p>
                            Als je denkt dat dit onterecht is of dat er een misverstand in het spel is, kun je de beheerders vragen om je account weer te activeren. Contacteer hen op via onderstaande knop.
                        </p>

                        <a href="mailto:beheerders@vlaamswoordenboek.be" class="btn btn-outline-primary">
                            Beheerders contacteren
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
