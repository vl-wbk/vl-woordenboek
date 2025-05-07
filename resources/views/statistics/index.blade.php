@extends('layouts.application-blank', ['title' => 'Statistieken'])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="color-green border-bottom pb-2">Insights in onze statistieken en performantie</h3>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        <div class="row">
            <div class="col-2">
                <div class="card border-0 shadow-sm card-body">
                    <h6 class="color-green fw-bold">Artikel weergaves</h6>
                    <h3 class="text-muted mb-0"><x-heroicon-o-eye class="icon icon-statistic"/> {{ $articleViews }}</h3>
                </div>
            </div>
            <div class="col-2">
                <div class="card border-0 shadow-sm card-body">
                    <h6 class="color-green fw-bold">Aantal artikelen</h6>
                    <h3 class="text-muted mb-0"><x-heroicon-o-document-text class="icon icon-statistic"/> {{ $articleCount }}</h3>
                </div>
            </div>
            <div class="col-2">
                <div class="card border-0 shadow-sm card-body">
                    <h6 class="color-green fw-bold">Aantal edits</h6>
                    <h3 class="text-muted mb-0"><x-heroicon-o-pencil-square class="icon icon-statistic"/> {{ $editCount }}</h3>
                </div>
            </div>
            <div class="col-2">
                <div class="card border-0 shadow-sm card-body">
                    <h6 class="color-green fw-bold">Aantal gebruikers</h6>
                    <h3 class="text-muted mb-0"><x-heroicon-o-users class="icon icon-statistic"/> {{ $getUserCount }}</h3>
                </div>
            </div>
            <div class="col-2">
                <div class="card border-0 shadow-sm card-body">
                    <h6 class="color-green fw-bold">Vrijwillligers</h6>
                    <h3 class="text-muted mb-0"><x-heroicon-o-users class="icon icon-statistic"/> {{ $getVolunteerCount }}</h3>
                </div>
            </div>
            <div class="col-2">
                <div class="card border-0 shadow-sm card-body">
                    <h6 class="color-green fw-bold">Registraties vandaag</h6>
                    <h3 class="text-muted mb-0"><x-heroicon-o-user-plus class="icon icon-statistic"/> {{ $getRegisteredToday }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card bg-white border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="pt-2 fw-bold color-green mb-1"><x-heroicon-o->Gebruikersevolutie</h5>
                <p class="mb-0 text-muted">Hoeveel accounts werden er het afgelopen jaar per week aangemaakt op het Vlaams woordenboek.</p>
            </div>
            <div class="card-body">
                <canvas id="myChart" style="max-height:250px;"></canvas>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card bg-white border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="pt-2 fw-bold color-green mb-1">Gebruikersevolutie</h5>
                <p class="mb-0 text-muted">Hoeveel account worden er aangemaakt op het Vlaams woordenboek?</p>
            </div>
            <div class="card-body">
                <canvas id="articleEdits" style="max-height:250px;"></canvas>
            </div>
        </div>
    </div>
@endsection

@section ('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart');
    const articleEdits = document.getElementById('articleEdits');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($userRegistrations['labels']),
            datasets: [{
                label: 'Registraties',
                data: @json($userRegistrations['data']),
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: { stacked: true },
                y: { stacked: true }

            }
        }
    });

    new Chart(articleEdits, {
        type: 'bar',
        data: {
            labels: @json($articleChart['labels']),
            datasets: [{
                label: 'Gearchiveerd',
                data: @json($articleChart['archived']),
                borderWidth: 1
            }, {
                label: 'Nieuwe artikelen',
                data: @json($articleChart['created']),
                borderWidth: 1
            }, {
                label: 'Publicaties',
                data: @json($articleChart['published']),
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: { stacked: true },
                y: { stacked: true }

            }
        }
    });
</script>
@endsection
