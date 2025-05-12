@extends('layouts.application-blank', ['title' => 'Versie informatie'])

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="color-green">Wijzigings informatie</h3>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-header bg-white color-green text-dark fw-bold">
                        Informatie omtrent de Bewerker
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <span class="fw-bold">Naam:</span><br>
                                {{ $audit->user->name }}
                            </div>
                            <div class="col-3">
                                <span class="fw-bold">Gebruikersgroep:</span><br>
                                {{ $audit->user->user_type->getLabel() }}
                            </div>
                            <div class="col-3">
                                <span class="fw-bold">Laatste aamelding:</span><br>
                                {{ $audit->user->last_seen_at }}
                            </div>
                            <div class="col-3">
                                <span class="fw-bold">Registratie datum:</span><br>
                                {{ $audit->user->created_at }}
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-header bg-white color-green text-dark fw-bold">
                        Meta gegegevens van de bewerking
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <span class="fw-bold">Artikel:</span><br>
                                <a href="{{ route('word-information.show', $audit->auditable) }}">{{ $audit->auditable->word }}</a>
                            </div>
                            <div class="col-3">
                                <span class="fw-bold">Handeling:</span><br>
                                {{  $audit->event }}
                            </div>
                            <div class="col-3">
                                <span class="fw-bold">Bewerkingstijdstip:</span><br>
                                {{ $audit->created_at->diffForHUmans() }}
                            </div>
                            <div class="col-3">
                                <span class="fw-bold">Bewerkt vanaf</span><br>
                                {{ $audit->ip_address }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mt-3">
                                <span class="fw-bold">User agent</span><br>
                                {{ $audit->user_agent }}
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-header bg-white color-green text-dark fw-bold">
                        Overzicht van de wijzigingen
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Kolom</th>
                                        <th scope="col">Oude waarde</th>
                                        <th scope="col">Nieuwe waarde</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($audit->getModified() as $field => $value)
                                        <tr>
                                            <th scope="row" class="text-muted" style="width: 30%;">{{ ucfirst($field) }}</th>
                                            <td style="width: 35%;" class="table-danger">{{ $value["old"] ?? '-' }}</td>
                                            <td style="width: 35%;" class="table-success">{{ $value["new"] ?? '-' }}</td>
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
@endsection
