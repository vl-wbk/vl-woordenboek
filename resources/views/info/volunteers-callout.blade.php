@extends ('layouts.application-blank', ['title' => 'Oproep naar vriijwilligers'])

@push('styles')
<style>
    /* Custom Underline Tabs */
    .nav-tabs-custom {
        gap: 2rem;
        border-bottom: 2px solid #f1f4f8;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 600;
        padding: 0.75rem 0;
        margin-bottom: -2px;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
    }

    .nav-tabs-custom .nav-link:hover { color: #0d6efd; }

    .nav-tabs-custom .nav-link.active {
        color: #0d6efd;
        background: transparent;
        border-bottom: 2px solid #0d6efd;
    }

    .nav-tabs-custom .icon, .role-card-icon {
        width: 1.25rem;
        height: 1.25rem;
    }

    /* Sidebar Sticky Offset */
    .sticky-sidebar { top: 2rem; }

    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); }
</style>
@endpush

@section ('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm border-bottom">
        <div class="container-fluid">
            <div class="py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <h1 class="display-6 fw-bold text-dark">Word vrijwilliger</h1>
                        <p class="fs-5 text-muted" style="max-width: 600px;">
                            Help ons het Vlaams erfgoed te bewaren en uit te bouwen.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section ('content')
<style>
        /* Custom Underline Tabs */
    .nav-tabs-custom {
        gap: 1rem;
        border-bottom: 2px solid #f1f4f8;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 600;
        padding: 0.75rem 0;
        margin-bottom: -2px;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
    }

    .nav-tabs-custom .nav-link:hover { color: #0d6efd; }

    .nav-tabs-custom .nav-link.active {
        color: #0d6efd;
        background: transparent;
        border-bottom: 2px solid #0d6efd;
    }

    .nav-tabs-custom .icon, .role-card-icon {
        width: 1.25rem;
        height: 1.25rem;
    }

    /* Sidebar Sticky Offset */
    .sticky-sidebar { top: 2rem; }

    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); }

</style>
    {{--
     |--------------------------------------------------------------------------
     | Mission Section
     |--------------------------------------------------------------------------
     --}}


    <section class="container-fluid py-4" id="rollen">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="mb-4">
                    <ul class="nav nav-tabs-custom">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <svg class="icon me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd"/><path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z"/></svg>
                                Open posities
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        {{--
                         |----------------------------------------------------------------------
                         | Result Cards: Volunteer Roles
                         |----------------------------------------------------------------------
                         --}}

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body bg-white">
                                <h5 class="card-title color-green fw-bold">Card title</h5>
    <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                            </div>
                            <div class="card-footer bg-light border-top-0">
                                <a href="#" class="btn btn-sm btn-outline-dark">Ik heb interesse</a>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h4 class="fw-bold mb-1 text-primary">Dialectverzamelaar</h4>
                                    <span class="badge bg-success-soft text-success">Open positie</span>
                                </div>
                                <p class="text-muted mb-3">
                                    Ga actief op zoek naar vergeten woorden in jouw regio. Spreek met ouderen, duik in lokale archieven en breng de taal tot leven.
                                </p>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="fe fe-check-circle me-2"></i>Passie voor lokale geschiedenis</li>
                                    <li><i class="fe fe-check-circle me-2"></i>Sociaal en nieuwsgierig</li>
                                </ul>
                            </div>
                            <div class="card-footer bg-white border-top-0 p-4 pt-0">
                                <a href="#" class="btn btn-sm btn-submit">Ik heb interesse</a>
                            </div>
                        </div>
                    </div>

                    <aside class="col-md-4">
                        <div class="sticky-top sticky-sidebar">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom py-3">
                                    <h5 class="card-title mb-0 fw-bold">Waarom helpen?</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small">Het Vlaams Woordenboek is een non-profit project. Jouw bijdrage zorgt ervoor dat ons dialect niet verloren gaat voor de volgende generaties.</p>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <h6 class="fw-bold">Nog vragen?</h6>
                                    <p class="small text-muted">Twijfel je welke rol bij je past? We helpen je graag verder.</p>
                                    <a href="mailto:contact@vlaamswoordenboek.be" class="btn btn-outline-primary btn-sm w-100">Contacteer ons</a>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection
