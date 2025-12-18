<?php

return [
    'jumbotron' => [
        'heading' => 'Uw suggesties in het <span class="text-warning">:applicationName</span>',
        'text' => [
            'first-sentence' => 'Het lijkt erop dat je al :count suggesties hebt aangeleverd of zijn gevonden in het Vlaams Woordenboek, waarvoor onze dank.',
            'second-sentence' => 'Via het onderstaande formulier kun je snuisteren tussen uw suggesties.'
        ],
        'form' => [
            'search-placeholder' => 'Zoeken tussen mijn suggesties',
            'buttons' => [
                'submit' => 'Zoeken',
                'reset' => 'Reset sortering'
            ],
            'current-search-term' => 'Zoekopdracht'
        ]
    ],
    'jumbotron.form.buttons.reset' => 'Sortering resetten',
    'jumbotron.form.search-placeholder' => 'Zoeken in mijn suggesties',
    'jumbotron.heading' => 'Je suggesties in het <span class="text-warning">:applicationName</span>',
    'jumbotron.text.first-sentence' => 'Er zijn :count suggesties van jou gevonden in het Vlaams Woordenboek, waarvoor onze dank.',
    'jumbotron.text.second-sentence' => 'Via het onderstaande formulier kun je in je eigen suggesties zoeken.',
    'no-results' => [
        'heading' => 'Geen suggesties gevonden',
        'first-sentence' => 'Als je nog geen suggesties hebt toegevoegd, blijft dit lijstje natuurlijk leeg.',
        'second-sentence' => 'Je hebt wel een lijst suggesties, maar je opzoeking levert niks op? Kijk dan even of je zoekterm klopt, voer iets anders in of pas je filters aan om meer resultaten te zien.'
    ],
    'no-results.first-sentence' => 'Als je nog geen suggesties hebt ingediend, blijft dit scherm natuurlijk leeg.',
    'no-results.second-sentence' => 'Je hebt wel suggesties ingediend, maar je opzoeking levert niks op? Kijk dan even of je zoekterm klopt, voer een andere zoekterm in of pas je filters aan. Wis eventueel alle zoektermen en schakel de filters uit.',
    'page-title' => 'Mijn suggesties',
    'sidenav' => [
        'headings' => [
            'submitted-since' => 'Ingestuurd sinds'
        ],
        'state-filters' => [
            'heading' => 'Filteren op status',
            'sggestion' => 'Suggestie',
            'draft' => 'Klad versie',
            'under-review' => 'In afwachting',
            'publication' => 'Publicatie'
        ]
    ],
    'sidenav.headings.submitted-since' => 'Wanneer ingestuurd',
    'sidenav.state-filters.draft' => 'Kladversie',
    'table' => [
        'columns' => [
            'status' => 'Status',
            'editor' => 'Redacteur',
            'lemma' => 'Lemma',
            'last-edited' => 'Laatste wijziging',
            'submitted_at' => 'Ingediend op'
        ],
        'actions' => [
            'view' => 'Bekijk'
        ]
    ]
];
