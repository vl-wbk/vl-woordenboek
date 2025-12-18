<?php

return [
    'actions' => [
        'view-action' => [
            'modal' => [
                'description' => 'Aangemaakt door :author op :date'
            ]
        ],
        'delete-action' => [
            'modal' => [
                'heading' => 'Notitie verwijderen',
                'description' => 'U staat op het punt om een notitie te verwijderen. Bent u zeker dat u deze actie wilt uitvoeren?',
                'submit-label' => 'Ja, ik ben zeker'
            ]
        ],
        'edit-action' => [
            'modal' => [
                'heading' => 'Notitie bewerken'
            ],
            'description' => 'Gegevens van een notitie dat gekoppeld is aan het woordenboek artikel bewerken.'
        ],
        'create-action' => [
            'label' => 'Notitie aanmaken',
            'modal' => [
                'description' => 'Toevoegen van een notitie aan het woordenboek artikel.'
            ]
        ],
        'bulk-delete' => [
            'modal' => [
                'heading' => 'Notitie(s) verwijderen',
                'submit-label' => 'Ja, ik ben zeker',
                'description' => 'U staat op het punt om een of meerdere notities te verwijderen. Bent u zeker dat u deze actie wilt uitvoeren?'
            ]
        ]
    ],
    'actions.bulk-delete.modal.description' => 'Je staat op het punt om een of meerdere notities te verwijderen. Ben je zeker dat je deze actie wilt uitvoeren?',
    'actions.bulk-delete.modal.submit-label' => 'Ja, ik ben zeker.',
    'actions.create-action.modal.description' => 'Een notitie aan dit woordenboekartikel toevoegen.',
    'actions.delete-action.modal.description' => 'Je staat op het punt om een notitie te verwijderen. Ben je zeker dat je deze actie wilt uitvoeren?',
    'actions.delete-action.modal.submit-label' => 'Ja, ik ben zeker.',
    'actions.edit-action.description' => 'Gegevens bewerken van een notitie die aan dit woordenboek is gekoppeld.',
    'colums' => [
        'author' => 'Auteur',
        'title' => 'Titel',
        'updated-at' => 'Laatst bewerkt',
        'created-at' => 'Registratie datum'
    ],
    'colums.created-at' => 'Registratiedatum',
    'description' => 'Overzicht van alle geregistreerde notities bij dit woordenboekartikel.',
    'empty-state' => [
        'heading' => 'Geen notities',
        'desciption' => 'Momenteel zijn er geen notities gevonden voor het woordenboek artikel.'
    ],
    'empty-state.desciption' => 'Er zijn momenteel geen notities voor dit woordenboekartikel.',
    'form' => [
        'title' => 'Titel',
        'body' => 'Notitie'
    ],
    'infolist' => [
        'body' => [
            'label' => 'Notitie'
        ]
    ],
    'table' => [
        'heading' => 'Notities'
    ]
];
