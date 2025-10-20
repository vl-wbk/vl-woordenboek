@extends ('layouts.application-blank', ['title' => 'Bewaarde woorden'])

@section ('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class="px-5 py-5">
                <div class="row">
                    <h1 class="display-6 fw-bold">Uw bewaarde woorden in het <span class="text-warning">Vlaams Woordenboek</span></h1>
                    <p class="col-12 fs-5 pb-3 border-bottom">
                        Het lijkt erop dat je {{ $results->total() }} woorden hebt bewaard in het Vlaams Woordenboek<br>
                        Via het onderstaande formulier kun je snuisteren tussen uw suggesties.
                    </p>

                    <form class="col-md-7 mt-4" action="{{ route('bookmarks:index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-lg-10 col-sm-8">
                                <input type="text" class="form-control bg-white shadow-sm" name="zoekterm" value="{{ request()->get('zoekterm') }}" placeholder="Zoeken tussen mijn bewaarde woorden" aria-label="searchterm">
                            </div>
                            <div class="col-lg-2 col-sm-4">
                                <button type="submit" class="btn shadow-sm w-100 btn-submit">
                                    <x-heroicon-o-magnifying-glass class="icon me-1"/> Zoeken
                                </button>
                            </div>
                            <div class="col-12">
                                <x-sortable-reset>
                                    Reset sortering
                                </x-sortable-reset>

                                @if (request()->has('zoekterm'))
                                    <a href="{{ route('suggestions:index') }}" class="btn btn-sm btn-outline-danger">
                                        <x-tabler-x class="icon me-1"/> Zoekopdracht: <strong>{{ request()->get('zoekterm') }}</strong>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section ('content')
   <div class="py-4">
       <div class="container-fluid">
           <div class="row">

               <div class="col-3">
                   <div class="card border-0 shadow-sm">
                       <div class="card-header bg-white">
                           <a href="{{ route('definitions.create') }}" class="btn btn-submit w-100 shadow-sm">
                               <x-heroicon-o-plus class="icon me-1"/> Suggestie indienen
                           </a>
                       </div>
                       <div class="card-body bg-white">
                           <ul class="list-unstyled mb-0">
                               <li class="d-flex justify-content-between align-items-center mb-2">
                                   <div class="d-flex align-items-center">
                                       <x-heroicon-o-pencil-square class="icon color-green me-2"/>

                                       <a href="{{ route('suggestions:index') }}" class="text-decoration-none text-muted">
                                           Mijn suggesties
                                       </a>
                                   </div>

                                   @auth
                                       <span class="badge text-bg-danger rounded-pill">
                                        {{ auth()->user()->suggestions->count() }}
                                    </span>
                                   @endauth
                               </li>

                               <li class="d-flex justify-content-between align-items-center">
                                   <div class="d-flex align-items-center">
                                       <x-heroicon-o-bookmark class="icon color-green me-2"/>

                                       <a href="{{ route('bookmarks:index') }}" class="text-decoration-none text-muted">
                                           Mijn bewaarde woorden
                                       </a>
                                   </div>

                                   @auth
                                       <span class="badge text-bg-danger rounded-pill">
                                        {{ auth()->user()->bookmarks->count() }}
                                    </span>
                                   @endauth
                               </li>
                           </ul>
                       </div>
                   </div>
               </div>

               <div class="col-9">
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
   </div>
@endsection
