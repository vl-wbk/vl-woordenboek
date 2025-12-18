<?php

return [
    'form' => [
        'name' => 'Naam v/d categorie',
        'description' => [
            'label' => 'Beschrijving van de categorie',
            'placeholder' => 'Beschrijf zo kort mogelijk waarover de categorie gaat'
        ]
    ],
    'form.description.placeholder' => 'Beschrijf de categorie zo kort mogelijk',
    'form.name' => 'Naam van de categorie',
    'infolist' => [
        'name' => 'Naam',
        'post-count' => 'Aantal koppelingen',
        'created-at' => 'Aangemaakt op',
        'description' => [
            'label' => 'Categorie beschrijving',
            'placeholder' => '- Geen categorie beschrijving opgegeven'
        ]
    ],
    'infolist.description.label' => 'Categoriebeschrijving',
    'infolist.description.placeholder' => '- Geen categoriebeschrijving opgegeven',
    'table' => [
        'heading' => 'Nieuws categorieen',
        'description' => 'Een overzicht van alle categorieen die kunnen gebruikt worden in onze nieuws berichten',
        'empty-state' => [
            'heading' => 'Geen categorieen gevonden',
            'description' => 'Het lijkt erop dat er momenteel nog geen categorieen zijn gevonden voor de nieuwsartikelen. Kom later nog eens terug.'
        ],
        'columns' => [
            'name' => 'Categorie',
            'posts_count' => 'Koppelingen',
            'description' => [
                'label' => 'Beschrijving',
                'placeholder' => '- Geen beschrijving opgegeven'
            ],
            'created-at' => 'Aangemaakt op'
        ],
        'header-actions' => [
            'create-action' => [
                'label' => 'Categorie toevoegen',
                'modal' => [
                    'heading' => 'Nieuwe categorie aanmaken',
                    'description' => 'Via het onderstaande formulier kunt u een nieuwe categorie aanmaken voor een nieuwsbericht'
                ]
            ],
            'factory-action' => [
                'modal' => [
                    'heading' => 'Categorieen genereren',
                    'description' => 'Genereer een aantal test categorieen voor nieuwberichten. Met als doel het de categorie functionaliteit te testen in het Vlaams Woordenboek. Deze actie kan niet ongedaan worden gemaakt.'
                ]
            ]
        ],
        'row-actions' => [
            'view-action' => [
                'tooltip' => 'Bekijken',
                'modal' => [
                    'heading' => 'Categorie informatie',
                    'description' => 'Een overzicht van alle gegevens die behoren tot de categorie'
                ]
            ],
            'edit-action' => [
                'tooltip' => 'Bewerken',
                'modal' => [
                    'heading' => 'Categorie wijzigen',
                    'description' => 'Via het onderstaande formulier kunt de gegevens wijzigen van de categorie'
                ]
            ],
            'delete-action' => [
                'tooltip' => 'Verwijderen',
                'modal' => [
                    'description' => 'Bij het verwijderen van de categorie zal deze automatisch verwijderd worden van bestaande nieuwsberichten. Weet je zeker dat je dit wilt doen?'
                ]
            ]
        ]
    ],
    'table.description' => 'Een overzicht van alle categorieën die in onze nieuwsberichten gebruikt kunnen worden',
    'table.empty-state.description' => 'Momenteel vinden we geen categorieën voor de nieuwsartikelen. Kom later nog eens terug.',
    'table.empty-state.heading' => 'Geen categorieën gevonden',
    'table.header-actions.create-action.modal.description' => 'Via het onderstaande formulier kun je een nieuwe categorie aanmaken voor een nieuwsbericht.',
    'table.header-actions.factory-action.modal.description' => 'Genereer enkele testcategorieën voor nieuwberichten. Zo kunnen we in het Vlaams Woordenboek testen of de categorieën werken. Deze actie kan niet ongedaan gemaakt worden.',
    'table.header-actions.factory-action.modal.heading' => 'Categorieën genereren',
    'table.heading' => 'Nieuwscategorieën',
    'table.row-actions.delete-action.modal.description' => 'Wanneer een categorie wordt verwijderd, zal die ook automatisch verdwijnen bij bestaande nieuwsberichten. Weet je zeker dat je dit wil doen?',
    'table.row-actions.edit-action.modal.description' => 'Via het onderstaande formulier kun je de gegevens van de categorie wijzigen.',
    'table.row-actions.view-action.modal.heading' => 'Categorie-informatie'
];
