<?php

return [
    'page-title' => 'Versie informatie',
    'page-heading' => 'Wijzigings informatie',
    'section' => [
        'editor' => [
            'heading' => 'Informatie omtrent de Bewerker',
            'columns' => [
                'name' => 'Naam',
                'user-group' => 'Gebruikersgroep',
                'last-seen-at' => 'Laatste aamelding',
                'registration-date' => 'Registratie datum',
            ],
        ],
        'changes' => [
            'heading' => 'Meta gegegevens van de bewerking',
            'columns' => [
                'article' => 'Artikel',
                'action' => 'Handeling',
                'edited-at' => 'Bewerkingstijdstip',
                'ip-address' => 'Bewerkt vanaf',
                'user-agent' => 'User agent',
            ],
        ],
        'difference' => [
            'heading' => 'Overzicht van de wijzigingen',
            'table' => [
                'heading' => [
                    'column' => 'Kolom',
                    'old-value' => 'Oude waarde',
                    'new-value' => 'Nieuwe waarde',
                ],
            ],
        ],
    ],
];
