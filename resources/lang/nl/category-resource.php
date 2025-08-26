<?php 

return [
    'table' => [
        'heading' => 'Nieuws categorieen',
        'description' => 'Een overzicht van alle categorieen die kunnen gebruikt worden in onze nieuws berichten',

        'empty-state' => [
            'heading' => 'Geen categorieen gevonden',
            'description' => 'Het lijkt erop dat er momenteel nog geen categorieen zijn gevonden voor de nieuwsartikelen. Kom later nog eens terug.',
        ],

        'columns' => [
            'name' => 'Categorie',
            'posts_count' => 'Koppelingen',
            'description' => [
                'label' => 'Beschrijving', 
                'placeholder' => '- Geen beschrijving opgegeven',
            ], 
            'created-at' => 'Aangemaakt op',
        ],

        'header-actions' => [
            'create-action' => [
                'label' => 'Categorie toevoegen',
                'modal' => [
                    'heading' => 'Nieuwe categorie aanmaken',
                    'description' => 'Via het onderstaande formulier kunt u een nieuwe categorie aanmaken voor een nieuwsbericht'
                ]
            ]
        ],

        'row-actions' => [
            'view-action' => [
                'tooltip' => 'Bekijken',

                'modal' => [
                    'heading' => 'Categorie informatie',
                    'description' => 'Een overzicht van alle gegevens die behoren tot de categorie',
                ],
            ],

            'edit-action' => [
                'tooltip' => 'Bewerken',

                'modal' => [
                    'heading' => 'Categorie wijzigen',
                    'description' => 'Via het onderstaande formulier kunt de gegevens wijzigen van de categorie',
                ],
            ],

            'delete-action' => [
                'tooltip' => 'Verwijderen',
                'modal' => [
                    'description' => 'Bij het verwijderen van de categorie zal deze automatisch verwijderd worden van bestaande nieuwsberichten. Weet je zeker dat je dit wilt doen?',
                ],
            ],
        ],
    ],

    'infolist' => [
        'name' => 'Naam', 
        'post-count' => 'Aantal koppelingen',
        'created-at' => 'Aangemaakt op',
        'description' => [
            'label' => 'Categorie beschrijving',
            'placeholder' => '- Geen categorie beschrijving opgegeven',
        ],
    ], 

    'form' => [
        'name' => 'Naam v/d categorie',
        'description' => [
            'label' => 'Beschrijving van de categorie',
            'placeholder' => 'Beschrijf zo kort mogelijk waarover de categorie gaat',
        ],
    ],
];