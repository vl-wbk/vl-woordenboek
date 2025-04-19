@extends ('layouts.application-blank', ['title' => 'Mijn suggesties'])

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="float-start">
                    <h2 class="color-green">Mijn suggesties</h2>
                </div>

                <x-articles.overview-toolbar/>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header color-green fw-bold bg-white">
                        Zoek tussen mijn suggesties
                    </div>

                    <div class="card-body">
                        <form action="{{ route('suggestions:index') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-10">
                                    <input type="text" class="form-control" name="zoekterm" value="{{ request()->get('zoekterm') }}" placeholder="Zoekterm" aria-label="searchterm">
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn w-100 btn-gradient btn-submit">
                                        <x-heroicon-o-magnifying-glass class="icon me-1"/> Zoeken
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="float-start mb-2">
                            <span class="fw-bold">{{ $results->total() }}</span> {{ trans_choice('{1} resultaat|[2,*] resultaten', $results->total()) }}

                            <a href="#" data-bs-toggle="tooltip" data-bs-title="Suggesties die zijn verwijderd door een admin worden hier niet vertoond." data-bs-placement="bottom">
                                <x-heroicon-s-question-mark-circle class="icon ms-1 color-green"/>
                            </a>
                        </div>

                        <div class="float-end mb-2">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item fw-bold text-muted">
                                    Filteren op:
                                </li>

                                @if (request()->has('filter'))
                                    <li class="list-inline-item">
                                        <a href="{{ request()->fullUrlWithoutQuery(['filter']) }}">
                                            <x-heroicon-o-x-circle class="icon text-danger"/> Reset filters
                                        </a>
                                    </li>

                                    <li class="list-inline-item">|</li>
                                @endif

                                <li class="list-inline-item">
                                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'new']) }}">
                                        <x-tabler-circle-plus class="icon color-green me-1"/> Onbehandeld
                                    </a>
                                </li>

                                <li class="list-inline-item">|</li>

                                <li class="list-inline-item">
                                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'inProgress']) }}">
                                        <x-tabler-circle class="icon color-green me-1"/> In behandeling
                                    </a>
                                </li>

                                <li class="list-inline-item">|</li>

                                <li class="list-inline-item">
                                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'done']) }}">
                                        <x-tabler-circle-check class="icon color-green me-1"/> Behandeld
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if ($results->total() > 0)
                    <div class="card bg-white border-0 border-bottom shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="ps-2">#</th>
                                            <th scope="col" class="text-muted">Status</th>
                                            <th scope="col" class="text-muted">Redacteur</th>
                                            <th scope="col" class="text-muted">Lemma</th>
                                            <th scope="col" class="text-muted">Laatste wijziging</th>
                                            <th scope="col" class="text-muted" colspan="2">Ingevoerd op</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results as $result)
                                            <tr>
                                                <th scope="row" class="color-green ps-2">#{{ $result->id }}</th>
                                                <td>
                                                    <span class="badge badge-{{ $result->state->getColor() }}">
                                                        {{ $result->state->getLabel() }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($result->editor()->exists())
                                                        {{ $result->editor->name }}
                                                    @else
                                                        <span class="color-green fst-italic">- geen</span>
                                                    @endif
                                                </td>

                                                <td>{{ $result->word }}</td>
                                                <td>
                                                    @if ($result->updated_at->eq($result->created_at))
                                                        <span class="color-green">-</span>
                                                    @else
                                                        {{ $result->created_at->format('d/m/Y H:i:s') }}
                                                    @endif
                                                </td>

                                                <td>{{ $result->updated_at->diffForHumans() }}</td>

                                                <td>
                                                    @if ($result->isPublished())
                                                        <a href="{{ route('word-information.show', $result) }}" class="text-muted me-2 text-decoration-none float-end">
                                                            <x-heroicon-o-eye class="icon me-1"/> Bekijk
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <x-definitions.pagination :results=$results />
                @else {{-- The user has no) filled in suggestions --}}
                    <div class="card bg-sidenav text-center shadow-sm border-0">
                        <div class="card-body p-4">
                            <x-tabler-inbox class="icon-blankslate color-green icon pb-3"/>
                            <h5 class="card-title fw-bold">Geen suggesties gevonden</h5>

                            <p class="card-text text-muted">
                                Oei!? Je hebt nog geen suggesties toegevoegd, of er is niks gevonden dat past bij je zoekterm of de filters die je gekozen hebt. <br>
                                Probeer eens iets anders in te geven of pas je filters aan om meer resultaten te zien.
                            </p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
