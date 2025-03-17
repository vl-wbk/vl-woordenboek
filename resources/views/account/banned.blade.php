@extends ('layouts.server-error-page', ['title' => '403 - Geen toegang'])

@section ('content')
<div class="container">
    <div class="hero text-center my-4">
        <h1 class="display-5"><i class="bi bi-exclamation-diamond text-danger mx-3"></i></h1>
        <h1 class="display-5 fw-bold">Geen toegang</h1>
        <p class="lead">De web pagina is niet toegankelijk met het aangemelde account op <em><span id="display-domain"></span></em>.</p>
    </div>

    <div class="content">
        <div class="row  justify-content-center py-3">
            <div class="col-md-7">
                <div class="my-5 p-3 card">
                    <div class="card-body">
                        <h3>Wat is er gebeurd?</h3>
                        <p class="">
                            Een administrator heeft ongewone activiteit waargenomen vanaf je account en of inbreuken van de algemene voorwaarden vastgesteld en daarop besloten om je account te deactiveren.
                        </p>

                        <p>
                            Indien je denkt dat dit een onterechte beslising en of misverstand is kan je altijd vragen aan de beheerders om deze beslissing ongedaan te maken. Dit kan doormiddel van de onderstaande knop.
                        </p>

                        <a href="mailto:admin@chimpy.be" class="btn btn-outline-primary">
                            Admins contacteren
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
