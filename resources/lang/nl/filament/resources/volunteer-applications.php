<?php 

return [
    'table' => [
        'heading' => 'Aanmeldingen voor een vrijwilligersrol',
        'description' => 'In de onderstaande table vind je een overzicht van alle aanmeldingen voor een rol als vrijwilliger in :app', 

        'columns' => [
            'state' => 'Status',
            'user' => 'Gebruiker',
            'reviewer' => 'Behandeld door',
            'position' => 'Gewenste positie',
            'created-at' => 'Aangemeld op',
            'closed-at' => 'Behandeld op',
        ],
    ],

    'infolist' => [
        'user-information' => [
            'heading' => 'Gebruikersinformatie',
        ], 

        'registration-info' => [
            'heading' => 'Aanmeldingsinformatie',
            'status-heading' => 'Status informatie',
            'background' => 'Taal achtergrond',
            'motivation' => 'Motivatie',
        ],
    ],

    "empty-state" => [
        'heading' => 'geen aanmeldingen gevonden', 
        'description' => 'Momenteel zijn er geen aanvragen gevonden matchende met je criteria. Kom later nog eens terug.'
    ],
    
    'actions' => [
        'factory' => [
            'label' => 'Dummy records aanmaken',
            'heading' => 'Test records genereren',
            'description' => 'Doormiddel van het onderstaande formulier kun je dummy aanmeldingen aanmaken die je kunt gebruiken om het systeem te testen. Ben je zeker dat je dit wilt doen?'
        ],
    ],
];