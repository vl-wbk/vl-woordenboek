@extends ('layouts.application-blank', ['title' => 'Oproep naar vriijwilligers'])

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card card-bg-white border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="color-green">{{ $pageSettings->pageTitle }}</h2>

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
@endsection
