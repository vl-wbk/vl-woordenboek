<?php

return [
    'actions' => [
        'deactivate-user' => [
            'label' => 'Deactiveer',
            'modal' => [
                'heading' => 'Gebruiker deactiveren',
                'form' => [
                    'comment' => 'Reden tot deactivering',
                    'expires-at' => 'Verloopt op'
                ]
            ],
            'buttons' => [
                'confirm' => 'Bevestigen'
            ]
        ],
        'generate' => [
            'heading' => 'Gebruikers genereren',
            'description' => 'Genereer een aantal test gebruikers in het Vlaams Woordenboek. Met als doel het de gebruikers functionaliteit te testen.'
        ],
        'reactivate-user' => [
            'label' => 'Reactiveer',
            'modal' => [
                'heading' => 'Gebruiker heractiveren'
            ],
            'buttons' => [
                'confirm' => 'Bevestigen'
            ]
        ]
    ],
    'actions.deactivate-user.modal.form.comment' => 'Reden tot deactivatie',
    'actions.generate.description' => 'Genereer een aantal testgebruikers in het Vlaams Woordenboek. Daarmee kun je gebruikersfunctionaliteiten testen.',
    'actions.reactivate-user.label' => 'Heractiveer',
    'buttons' => [
        'create-user' => 'Gebruiker toevoegen'
    ],
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
                    'helper_text' => 'Deze groepen bepalen wie tot welke zaken toegang heeft in het Vlaams woordenboek. Laat dit leeg als het om het gewone gebruiker gaat die het woordenboek enkel bezoekt.'
                ]
            ]
        ]
    ],
    'form.section.description' => 'Vul hier alle benodigde informatie in om een nieuwe gebruiker aan te maken op het Vlaams Woordenboek',
    'form.section.inputs.email' => 'E-mailadres',
    'form.section.inputs.roles.helper_text' => 'Deze groepen bepalen wie wat kan doen in het Vlaams Woordenboek. Laat dit leeg als het om het gewone gebruiker met account gaat die het woordenboek enkel bezoekt.',
    'form.section.inputs.roles.label' => 'Permissiegroepen',
    'form.section.inputs.roles.max_items_message' => 'Er kunnen maximum :max permissiegroepen voor een gebruiker geselecteerd worden.',
    'form.section.inputs.user_type' => 'Gebruikersgroep',
    'infolist' => [
        'tabs' => [
            'general' => 'Algemene informatie',
            'deactivation' => 'Deactiverings informatie'
        ],
        'deactivation-information' => [
            'entries' => [
                'bannable' => 'Gedeactiveerd door',
                'banned_at' => 'Gedeactiveerd sinds',
                'expires_at' => 'Heractiverings datum',
                'reason' => [
                    'label' => 'Redenen tot deactivering',
                    'placeholder' => '- Geen reden opgegeven'
                ]
            ]
        ]
    ],
    'infolist.deactivation-information.entries.expires_at' => 'Heractiveringsdatum',
    'infolist.tabs.deactivation' => 'Deactiveringsinformatie',
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
                'placeholder' => '- geen toegewezen'
            ]
        ],
        'filters' => [
            'user-type' => 'Gebruikers groep'
        ]
    ],
    'tables.columns.email' => 'E-mailadres',
    'tables.columns.roles.label' => 'Gebruikersrol',
    'tables.columns.roles.placeholder' => '- niets toegewezen',
    'tables.columns.user-type' => 'Gebruikersgroep',
    'tables.filters.user-type' => 'Gebruikersgroep'
];
