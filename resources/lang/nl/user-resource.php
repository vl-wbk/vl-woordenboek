<?php

return [
    'form' => [
        'section' => [
            'heading' => 'Nieuwe gebruiker aanmaken',
            'description' => 'Vul hier alle benodigde informatie in voor het aanmaken van een nieuwe gebruiker op het Vlaams woordenboek',

            'inputs' => [
                'user_type' => 'Gebruikers groep',
                'firstname' => 'Voornaam',
                'lastname' => 'Achternaam',
                'email' => 'E-mail adres',
                'roles' => [
                    'label' => 'Permissie groepen',
                    'max_items_message' => 'Er kunnen maar maximum :max permissie groepen voor een gebruiker geselecteerd worden',
                    'helper_text' => 'Deze groepen bepalen wie tot welke zaken toegang heeft in het Vlaams woordenboek. Laat dit leeg als het om het gewone gebruiker gaat die het woordenboek enkel bezoekt.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'tabs' => [
            'general' => 'Algemene informatie',
            'deactivation' => 'Deactiverings informatie',
        ],

        'deactivation-information' => [
            'entries' => [
                'bannable' => 'Gedeactiveerd door',
                'banned_at' => 'Gedeactiveerd sinds',
                'expires_at' => 'Heractiverings datum',
                'reason' => [
                    'label' => 'Redenen tot deactivering',
                    'placeholder' => '- Geen reden opgegeven',
                ],
            ],
        ],
    ],

    'tables' => [
        'heading' => 'Gebruikersbeheer',
        'description' => 'In dit overzicht zie je alle geregistreerde gebruikers van het systeem. Je kunt hier gebruikersgegevens bekijken, accounts bewerken, rollen toewijzen of gebruikers verwijderen. Gebruik de zoek- en filteropties om snel de juiste gebruiker te vinden.',

        'columns' => [
            'name' => 'Naam',
            'email' => 'E-mail adres',
            'user-type' => 'Gebruikers groep',
            'last-seen-at' => 'Laatste aanmelding',
            'created-at' => 'Registratiedatum',
            'roles' => [
                'label' => 'Gebruikers rol',
                'placeholder' => '- geen toegewezen',
            ],
        ],

        'filters' => [
            'user-type' => 'Gebruikers groep',
        ],
    ],

    'buttons' => [
        'create-user' => 'Gebruiker toevoegen',
    ],

    'actions' => [
        'deactivate-user' => [
            'label' => 'Deactiveer',

            'modal' => [
                'heading' => 'Gebruiker deactiveren',
                'form' => [
                    'comment' => 'Reden tot deactivering',
                    'expires-at' => 'Verloopt op',
                ],
            ],

            'buttons' => [
                'confirm' => 'Bevestigen',
            ],
        ],

        'reactivate-user' => [
            'label' => 'Reactiveer',

            'modal' => [
                'heading' => 'Gebruiker heractiveren',
            ],

            'buttons' => [
                'confirm' => 'Bevestigen',
            ],
        ],
    ],
];
