@extends ('layouts.application-blank', ['title' => 'Etymology'])

@section('content')
<div class="container">
    <div class="row mb-1">
        <div class="col-12">
            <div class="float-start">
                <h3>
                    <a href="{{ route('word-information.show', $etymology->article) }}" class="text-muted text-decoration-none">
                        <x-heroicon-o-arrow-uturn-left class="icon icon-back-to-results"/>
                    </a>

                    <span class="text-muted">/</span>
                    Etymologie gegevens van: <span class="color-green fw-bold">{{ $etymology->article->word }}</span>
                </h3>

                <p class="text-muted mb-3">Overzicht van de historische herkomst(en) van het woord</p>
            </div>
        </div>

        <div class="col-12">
            <ul class="list-inline mb-0">
                <li class="list-inline-item"><span class="color-green">ID:</span> <span class="fw-bold">#ETYM-{{ $etymology->id }}</span></li>
                <li class="list-inline-item text-muted">|</li>
                <li class="list-inline-item"><span class="color-green">Aantal herkomsten:</span> <span class="fw-bold">{{ $etymology->article->etymologies->count() }}</span></li>
                <li class="list-inline-item text-muted">|</li>
                <li class="list-inline-item"><span class="color-green">Aangemaakt op:</span> <span class="fw-bold">{{ $etymology->created_at->format('d/m/Y') }}</span></li>
                <li class="list-inline-item text-muted">|</li>
                <li class="list-inline-item"><span class="color-green">Laatste wijziging:</span> <span class="fw-bold">{{ $etymology->updated_at->diffForHumans() }}</span></li>
            </ul>
        </div>
    </div>

    <hr class="mt-2 mb-3">

    <div class="row">
        <div class="col-12">
            <ul class="nav nav-underline mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ active('etymology:show') }}" aria-current="page" href="{{ route('etymology:show', $etymology) }}">
                        <x-heroicon-o-queue-list class="icon me-1"/> Gegevensoverzicht
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ active('etymology:create') }}" href="{{ route('etymology:create', $etymology->article) }}">
                        <x-heroicon-o-pencil-square class="icon me-1"/> Nieuwe suggestie doen
                    </a>
                </li>
            </ul>

            @if (flash()->message)
                <div class="alert {{ flash()->class }} alert-dismissible fade show border-0 shadow-sm" data-bs-dismiss="alert">
                    <x-heroicon-o-bell-alert class="icon icon-lg me-1"/> Gelukt!
                    {{ flash()->message }}
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header text-dark border-0 bg-sidenav">
                    <h5 class="mb-0">
                        Herkomst uit het {{ $etymology->origin_language }}: <em class="fw-bold color-green">{{ $etymology->origin_form }}</em>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-0">
                        <div class="col-4 mb-0">
                            <dl>
                                <dt>Periode:</dt>
                                <dd>{{ $etymology->period }}</dd>
                                <dt>Bron</dt>
                                <dd class="mb-0">
                                    @if ($etymology->source_url)
                                        <a href="{{ $etymology->source_url }}">
                                            {{ $etymology->source }}
                                        </a>
                                    @else
                                        {{ $etymology->source }}
                                    @endif
                                </dd>
                            </dl>
                        </div>
                        <div class="col-8 mb-0">
                            <dl>
                                <dt>Uitleg:</dt>
                                <dd class="mb-0">{{ $etymology->etymology }}</dd>
                            </dl>
                        </div>

                        @if ($etymology->note)
                            <div class="col-12">
                                <button class="btn btn-sm btn-outline-success mt-2" data-bs-toggle="collapse" data-bs-target="#latijnNote" aria-expanded="false" aria-controls="latijnNote">
                                    <x-heroicon-s-information-circle class="icon me-1"/>Toelichting
                                </button>

                                <div class="collapse mt-2" id="latijnNote">
                                    <div class="card card-body bg-sidenav border-0 shadow-sm small">
                                            {{ $etymology->note }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm bg-white border-0 mt-4">
                    <div class="card-header border-0 bg-sidenav">
                        <span class="color-green fw-bold">
                            <x-heroicon-o-queue-list class="icon me-1"/>Overzichtstabel van de herkomsten <br>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-muted">#</th>
                                        <th class="text-muted">Periode</th>
                                        <th class="text-muted">Status</th>
                                        <th class="text-muted">Type</th>
                                        <th class="text-muted">Taal</th>
                                        <th class="text-muted">Vorm</th>
                                        <th class="text-muted" colspan="2">Bron</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($etymologies as $etymology)
                                        <tr>
                                            <th scope="row"><code>#ETYM-{{ $etymology->id }}</code></th>
                                            <td class="fw-bold color-green">{{ $etymology->period }}</td>
                                            <td>
                                                <span class="badge {{ $etymology->status->frontendBadge() }}">
                                                    {{ $etymology->status->getLabel() }}
                                                </span>
                                            </td>
                                            <td>{{ $etymology->source_name->getLabel() }}</td>
                                            <td>{{ $etymology->origin_language }}</td>
                                            <td>{{ $etymology->origin_form }}</td>
                                            <td>
                                                <a href="{{ $etymology->source_url }}">
                                                    {{ $etymology->source }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="float-end">
                                                    <a href="{{ route('etymology:show', $etymology) }}" class="text-muted">
                                                        <x-heroicon-o-eye class="icon me-1"/>Bekijk informatie
                                                    </a>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
