@extends ('layouts.application-blank', ['title' => 'Bewaarde woorden'])

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="float-start">
                    <h3 class="color-green">Mijn bewaarde woorden</h3>
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
                        Zoeken tussen mijn bewaarde woorden
                    </div>

                    <form method="GET" action="{{ route('bookmarks:index') }}" class="card-body">
                        <div class="row g-3">
                            <div class="col-10">
                                <input type="text" class="form-control" name="zoekterm" value="{{ request()->get('zoekterm') }}" placeholder="Zoekterm" aria-label="searchTerm">
                            </div>
                            <div class="col-2">
                                <button type="submît" class="btn w-100 btn-gradient btn-submit">
                                    <x-heroicon-o-magnifying-glass class="icon me-1"/> Zoeken
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <hr>

                <div class="row">
                    <div class="col-12">
                        @if ($results->total() > 0)
                            <div class="card bg-white border-0 border-bottom shadow-sm">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-muted ps-2">Lemma</th>
                                                    <th scope="col" class="text-muted">Weergaves</th>
                                                    <th scope="col" class="text-muted">Beschrijving</th>
                                                    <th scope="col" colspan="2" class="text-muted">Laatste wijziging</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($results as $result)
                                                    <tr>
                                                        <th scope="row" class="color-green ps-2">{{ ucfirst($result->word) }}</th>
                                                        <td><x-heroicon-o-eye class="icon me-1 color-green"/> {{ $result->views }}</td>
                                                        <td>{{ str()->limit(strip_tags($result->description), 50) }}</td>
                                                        <td>{{ $result->updated_at->diffForHumans() }}</td>

                                                        <td>
                                                            <a href="{{ route('bookmark:remove', $result) }}" class="text-danger me-2 text-decoration-none float-end">
                                                                <x-heroicon-o-trash class="icon me-1"/>
                                                            </a>

                                                            @if ($result->isPublished())
                                                                <a href="{{ route('word-information.show', $result) }}" class="text-muted me-2 text-decoration-none float-end">
                                                                    <x-heroicon-o-eye class="icon me-1"/>
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
                                    <x-tabler-bookmark class="icon-blankslate color-green icon pb-3"/>
                                    <h5 class="card-title fw-bold">Geen bewaarde woorden gevonden</h5>

                                    <p class="card-text text-muted">
                                        Als je nog geen hebt bewaard, blijft dit lijstje natuurlijk leeg.
                                        Je hebt wel een lijst met bewaarde woorden, maar je opzoeking levert niks op? Kijk dan even of je zoekterm klopt, voer iets anders in of pas je filters aan om meer resultaten te zien.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
