@extends ('layouts.server-error-page', ['title' => __('errors/maintenance.page-title')])

@section ('content')
    <div class="container">
        <div class="hero text-center my-4">
            <h1 class="display-5"><i class="bi bi-tools text-danger mx-3"></i></h1>
            <h1 class="display-5 fw-bold">{{ __('errors/maintenance.page-heading') }}</h1>
            <p class="lead">{{ __('errors/maintenance.lead-paragraph') }}</p>
        </div>

        <div class="content">
            <div class="row  justify-content-center py-3">
                <div class="col-md-7">
                    <div class="my-5 p-3 card shadow">
                        <div class="card-body">
                            <h3>{{ __('errors/maintenance.description.heading') }}</h3>

                            <p>{{ __('errors/maintenance.description.first-paragraph') }}</p>
                            <p>{{ __('errors/maintenance.description.second-paragraph') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
