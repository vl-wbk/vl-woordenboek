<x-public-profile :user="$user">
    @if ($contributions->total() > 0)
        <div class="card border-0 bg-white shadow-sm">
            <div class="card-header bg-light-subtle border-bottom">
                <form action="" class="row g-2 g-sm-3 align-items-stretch">
                    <div class="col-12 col-md">
                        <input
                            type="text"
                            class="form-control bg-white"
                            name="zoekterm"
                            value="{{ request()->get('zoekterm') }}"
                            placeholder="Zoekterm"
                            aria-label="Zoekterm"
                        >
                    </div>

                    <div class="col-12 col-md-auto">
                        <button type="submit" class="btn btn-gradient btn-submit w-100">
                            <x-heroicon-o-magnifying-glass class="icon me-1"/> Zoeken
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead>
                        <tr>
                            <th scope="col" class="border-top-0 text-muted">Woord</th>
                            <th scope="col" class="border-top-0 text-muted d-none d-sm-table-cell">Weergaves</th>
                            <th scope="col" class="border-top-0 text-muted d-none d-md-table-cell">Karakteristieken</th>
                            <th scope="col" class="border-top-0 text-muted" colspan="2">Publicatiedatum</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contributions as $contribution)
                            <tr>
                                <th class="color-green text-break" scope="row">
                                    {{ $contribution->word }}
                                </th>
                                <td class="d-none d-sm-table-cell">
                                    {{ toHumanReadableNumber($contribution->views) }}
                                </td>
                                <td class="d-none d-md-table-cell text-truncate" style="max-width: 320px;">
                                    {{ $contribution->characteristics }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ $contribution->published_at->format('d/m/Y') }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('word-information.show', $contribution) }}" class="text-muted text-decoration-none btn btn-link p-0">
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

        <x-definitions.pagination :results="$contributions" />
    @elseif($contributions->total() === 0 && request()->has('zoekterm'))
        <div class="card bg-sidenav text-center shadow-sm border-0">
            <div class="card-body p-4">
                <x-heroicon-o-book-open class="icon-blankslate color-green icon pb-3"/>
                <h5 class="card-title fw-bold">Geen resultaten gevonden</h5>

                <p class="card-text text-muted mb-3">
                    Er zijn geen resultaten gevonden voor zoekopdracht met de term
                    <span class="fst-italic fw-bold">{{ request()->string('zoekterm') }}</span>.
                    Probeer het eens met een andere zoekterm of maak de zoekopdracht ongedaan met de onderstaande knop.
                </p>

                <a href="{{ route('account:public', $user) }}" class="btn btn-submit w-100 w-sm-auto">
                    <x-heroicon-o-arrow-left class="icon me-1"/> Ga terug naar het overzicht
                </a>
            </div>
        </div>
    @else
        <div class="card bg-sidenav text-center shadow-sm border-0">
            <div class="card-body p-4">
                <x-heroicon-o-book-open class="icon-blankslate color-green icon pb-3"/>
                <h5 class="card-title fw-bold">Geen gepubliceerde artikelen</h5>

                <p class="card-text text-muted">
                    Het lijkt erop dat {{ $user->name }} nog geen suggesties tot nieuwe artikelen in {{ config('app.name') }} heeft toegevoegd die zijn nagekeken en gepubliceerd.
                </p>
            </div>
        </div>
    @endif
</x-public-profile>
