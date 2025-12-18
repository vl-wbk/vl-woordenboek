<?php

return [
    'page-heading' => 'Wijzigingsinformatie',
    'page-title' => 'Informatie over de versie',
    'section' => [
        'editor' => [
            'heading' => 'Informatie omtrent de Bewerker',
            'columns' => [
                'name' => 'Naam',
                'user-group' => 'Gebruikersgroep',
                'last-seen-at' => 'Laatste aamelding',
                'registration-date' => 'Registratie datum'
            ]
        ],
        'changes' => [
            'heading' => 'Meta gegegevens van de bewerking',
            'columns' => [
                'article' => 'Artikel',
                'action' => 'Handeling',
                'edited-at' => 'Bewerkingstijdstip',
                'ip-address' => 'Bewerkt vanaf',
                'user-agent' => 'User agent'
            ]
        ],
        'difference' => [
            'heading' => 'Overzicht van de wijzigingen',
            'table' => [
                'heading' => [
                    'column' => 'Kolom',
                    'old-value' => 'Oude waarde',
                    'new-value' => 'Nieuwe waarde'
                ]
            ]
        ]
    ],
    'section.changes.columns.action' => 'Actie',
    'section.changes.columns.edited-at' => 'Tijdstip bewerking',
    'section.changes.heading' => 'Metagegegevens van de bewerking',
    'section.editor.columns.last-seen-at' => 'Laatste aanmelding',
    'section.editor.columns.registration-date' => 'Registratiedatum',
    'section.editor.heading' => 'Informatie omtrent de bewerker'
];
