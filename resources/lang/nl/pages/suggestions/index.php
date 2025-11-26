<?php

return [
    'page-title' => 'Mijn suggesties',
    'jumbotron' => [
        'heading' => 'Uw suggesties in het <span class="text-warning">:applicationName</span>',
        'text' => [
            'first-sentence' => 'Het lijkt erop dat je al :count suggesties hebt aangeleverd of zijn gevonden in het Vlaams Woordenboek, waarvoor onze dank.',
            'second-sentence' => 'Via het onderstaande formulier kun je snuisteren tussen uw suggesties.',
        ],
        'form' => [
            'search-placeholder' => 'Zoeken tussen mijn suggesties',
            'buttons' => [
                'submit' => 'Zoeken',
                'reset' => 'Reset sortering',
            ],
            'current-search-term' => 'Zoekopdracht',
        ],
    ],
    'sidenav' => [
        'headings' => [
            'submitted-since' => 'Ingestuurd sinds',
        ],
        'state-filters' => [
            'heading' => 'Filteren op status',
            'sggestion' => 'Suggestie',
            'draft' => 'Klad versie',
            'under-review' => 'In afwachting',
            'publication' => 'Publicatie',
        ],
    ],
    'table' => [
        'columns' => [
            'status' => 'Status',
            'editor' => 'Redacteur',
            'lemma' => 'Lemma',
            'last-edited' => 'Laatste wijziging',
            'submitted_at' => 'Ingediend op',
        ],
        'actions' => [
            'view' => 'Bekijk',
        ],
    ],
    'no-results' => [
        'heading' => 'Geen suggesties gevonden',
        'first-sentence' => 'Als je nog geen suggesties hebt toegevoegd, blijft dit lijstje natuurlijk leeg.',
        'second-sentence' => 'Je hebt wel een lijst suggesties, maar je opzoeking levert niks op? Kijk dan even of je zoekterm klopt, voer iets anders in of pas je filters aan om meer resultaten te zien.',
    ],
];
