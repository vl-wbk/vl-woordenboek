<?php

return [
    'table' => [
        'columns' => [
            'status' => 'Status',
            'created-at' => 'Gemeld op',
            'reported-by' => 'Gemeld door',
        ],
        'empty-state' => [
            'heading' => 'Geen meldingen gevonden',
            'description' => 'Het lijk erop dat er momenteel geen openstaande meldingen zijn die gerelateerd zijn aan de atikelen van het Vlaams Woordenboek.',
        ],
        'description' => 'Soms kan het zijn dat er een foutje sluipt in een woordenboek artikel en gebruikers deze melden. Deze table is een overzicht van alle meldingen die zijn uitgevoerd door een gebruiker.',
        'filters' => [
            'status' => 'Status',
            'assigned' => 'Toegewezen aan mij',
        ],
    ],
    'fieldsets' => [
        'feedback' => [
            'label' => 'Door de gebruiker gegeven feedback',
        ],

        'follow-up' => [
            'label' => 'Gegevens omtrent de opvolging',
            'entries' => [
                'status' => 'Status',
                'assignee' => 'Opgevolgd door',
                'assignee-placeholder' => 'geen opvolger geregistreerd',
                'assigned-at' => 'Toegewezen op',
                'closed-at' => 'Afgesloten op',
            ],
        ]
    ],
];
