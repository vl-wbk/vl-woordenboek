@extends ('layouts.application-blank', ['title' => 'Oproep naar vriijwilligers'])

@section ('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class="py-5">
                <div class="row">
                    <h1 class="display-6 fw-bold">
                        {{ $pageSettings->pageTitle }}
                    </h1>

                    <p class="col-12 fs-5 text-muted">Goesting om een handje toe te steken in het Vlaams Woordenboek?</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section ('content')
    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-bg-white border-0 shadow-sm">
                        <div class="card-body">
                            <div class="volunteers-description">
                                {!! str($pageSettings->pageContent)->markdown()->sanitizeHtml() !!}
                            </div>

                            <hr>

                            <h4 class="color-green">Open posities</h4>

                            @if (collect($pageSettings->positions)->count() > 0)
                                <p class="card-text">
                                    We zijn nog op zoek naar vrijwilligers voor de volgende functies. Heb je interesse? Laat het ons weten via het contactformulier op onze website.
                                </p>

                                <div class="row mt-3">
                                    @foreach ($pageSettings->positions as $position)
                                        @php($positionInfo = \App\Enums\VolunteerPositions::tryFrom($position))

                                        <div class="col-4">
                                            <div class="card h-100 border-0 bg-sidenav shadow-sm">
                                                <div class="card-body">
                                                    <h5 class="card-title gst-italic fw-bold color-green">{{ $positionInfo->getLabel() }}</h5>
                                                    <p class="card-text lh-small">{{ $positionInfo->getDescription() }}</p>

                                                    @auth {{-- Only authenticated users should apply for the position --}}
                                                        <a href="{{ route('support.volunteers.submit', ['volunteerPositions' => $position]) }}" class="btn shadow-sm btn-outline-secondary mt-3">
                                                            Ik heb intresse!
                                                        </a>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
