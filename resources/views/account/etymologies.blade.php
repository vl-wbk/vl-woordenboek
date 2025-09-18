<x-public-profile :user=$user>
    @if ($contributions->total() > 0)
        <div class="card border-0 bg-white shadow-sm">
            <div class="card-header bg-light-subtle border-bottom">
                <form action="" class="d-flex justify-content-start gap-3">
                    <input type="text" class="form-control bg-white w-75" name="zoekterm" value="{{ request()->get('zoekterm') }}" placeholder="Zoekterm" aria-label="searchterm">

                    <button type="submit" class="btn w-25 btn-gradient btn-submit">
                        <x-heroicon-o-magnifying-glass class="icon me-1"/> Zoeken
                    </button>

                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table-sm table-hover table mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="border-top-0 text-muted">Gekoppeld artikel</th>
                                <th scope="col" class="border-top-0 text-muted">Oorsprong periode</th>
                                <th scope="col" class="border-top-0 text-muted">Oorsprong</th>
                                <th scope="col" class="border-top-0 text-muted" colspan="2">Publicatiedatum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contributions as $contribution)
                                <tr>
                                    <th scope="row">
                                        <a href="{{ route('word-information.show', $contribution->article) }}" class="color-green text-decoration-none">
                                            {{ $contribution->article->word }}
                                        </a>
                                    </th>

                                    <td>{{ $contribution->origin_period }}</td>
                                    <td>{{ $contribution->origin }}</td>
                                    <td>{{ $contribution->published_at->format('d/m/Y') }}</td>

                                    <td>
                                        <a href="{{ route('word-information.show', $contribution->article) }}" class="color-green float-end text-decoration-none">
                                            <x-heroicon-o-eye class="icon me-1"/> Bekijken
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-definitions.pagination :results=$contributions />
    @elseif($contributions->total() === 0 && request()->has('zoekterm'))
        <div class="card bg-sidenav text-center shadow-sm border-0">
            <div class="card-body p-4">
                <x-heroicon-o-book-open class="icon-blankslate color-green icon pb-3"/>
                <h5 class="card-title fw-bold">Geen resultaten gevonden</h5>

                <p class="card-text text-muted mb-3">
                    Er zijn geen resultaten gevonden voor zoekopdracht met de term <span class="fst-italic fw-bold">{{ request()->string('zoekterm') }}</span>. Probeer het eens met een andere zoekterm of maak de zoekopdracht ongedaan met de onderstaande knop.
                </p>

                <a href="{{ route('account:public:etymologies', $user) }}" class="btn btn-submit">
                    <x-heroicon-o-arrow-left class="icon me-1"/> Ga terug naar het overzicht
                </a>
            </div>
        </div>
    @else
        <div class="card bg-sidenav text-center shadow-sm border-0">
            <div class="card-body p-4">
                <x-heroicon-o-book-open class="icon-blankslate color-green icon pb-3"/>
                <h5 class="card-title fw-bold">Geen gepubliceerde bijdrages</h5>

                <p class="card-text text-muted">
                    Het lijkt erop dat {{ $user->name }} nog geen suggesties tot nieuwe etymologieën in {{ config('app.name') }} heeft toegevoegd die zijn nagekeken en gepubliceerd.
                </p>
            </div>
        </div>
    @endif
</x-public-profile>