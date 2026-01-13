@extends ('layouts.application-blank', ['title' => 'Oproep naar vrijwilligers'])

@section ('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm border-bottom">
        <div class="container-fluid">
            <div class="py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <h1 class="display-6 fw-bold text-dark">{{ $pageSettings->pageTitle }}</h1>
                        <p class="fs-5 text-muted" style="max-width: 600px;">
                            {{ $pageSettings->pageTagLine }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section ('content')
    <section class="container-fluid py-4" id="vrijwilligers-sectie">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{--
                 |--------------------------------------------------------------------------
                 | Navigation Tabs
                 |--------------------------------------------------------------------------
                 --}}
                <div class="mb-4">
                    <ul class="nav nav-tabs-custom" id="volunteerTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="positions-tab" data-bs-toggle="tab" data-bs-target="#positions" type="button" role="tab">
                                <x-heroicon-s-queue-list class="icon me-2"/> Open posities
                            </button>
                        </li>

                        @if ($pageSettings->pageSelectionProcedureActive)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="procedure-tab" data-bs-toggle="tab" data-bs-target="#procedure" type="button" role="tab">
                                    <x-heroicon-s-clipboard-document-check class="icon me-2"/> Selectieprocedure
                                </button>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="tab-content" id="volunteerTabContent">

                            {{--
                             |----------------------------------------------------------------------
                             | Tab: Open Positions
                             |----------------------------------------------------------------------
                             --}}
                            <div class="tab-pane fade show active" id="positions" role="tabpanel">

                                    <div class="card border-0 bg-white shadow-sm mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title color-green fw-bold">titel</h5>
                                            <h6 class="card-subtitle mb-2 text-body-secondary">category</h6>
                                            <p class="card-text text-muted">description</p>
                                        </div>
                                        <div class="card-footer bg-light border-top-0">
                                            <a href="" class="btn btn-sm btn-outline-dark">Ik heb interesse</a>
                                        </div>
                                    </div>

                            </div>

                            {{--
                            |----------------------------------------------------------------------
                            | Tab: Selection Procedure
                            |----------------------------------------------------------------------
                            --}}
                            @if ($pageSettings->pageSelectionProcedureActive)
                                <div class="tab-pane fade" id="procedure" role="tabpanel">
                                    @foreach ($pageSettings->procedure as $step)
                                        <div class="card border-0 bg-white shadow-sm mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title color-green fw-bold">{{ $loop->iteration }}. {{ $step['title'] }}</h5>

                                                @if ($step['subtitle'])
                                                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $step['subtitle'] }}</h6>
                                                @endif

                                                <p class="card-text text-muted">
                                                    {{ $step['description'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach



                                </div>
                            @endif
                         </div>
                    </div>

                    {{--
                     |--------------------------------------------------------------------------
                     | Sidebar
                     |--------------------------------------------------------------------------
                     --}}
                    <aside class="col-md-4">
                        <div class="sticky-top sticky-sidebar">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header border-0 bg-sidenav">
                                    <h5 class="color-green mb-0">{{ $pageSettings->whyHelpTitle }}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted">{{ $pageSettings->whyHelpContent }}</p>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-header border-0 bg-sidenav">
                                    <h5 class="fw-bold color-green mb-0">{{ $pageSettings->questionsTitle }}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted">{{ $pageSettings->questionsContent }}</p>
                                </div>
                                <div class="card-footer bg-light">
                                     <a href="mailto:{{ $pageSettings->questionsEmail }}" class="btn btn-outline-primary btn-sm w-100">Contacteer ons</a>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection
