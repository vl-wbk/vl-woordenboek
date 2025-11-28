<?php

return [
    'form' => [
        'title' => 'Titel',
        'body' => 'Notitie',
    ],
    'infolist' => [
        'body' => [
            'label' => 'Notitie',
        ],
    ],
    'table' => [
        'heading' => 'Notities',
    ],
    'description' => 'Overzicht van alle geregistreerde notities omtrent het woordenboek artikel.',
    'empty-state' => [
        'heading' => 'Geen notities',
        'desciption' => 'Momenteel zijn er geen notities gevonden voor het woordenboek artikel.',
    ],
    'actions' => [
        'view-action' => [
            'modal' => [
                'description' => 'Aangemaakt door :author op :date',
            ],
        ],
        'delete-action' => [
            'modal' => [
                'heading' => 'Notitie verwijderen',
                'description' => 'U staat op het punt om een notitie te verwijderen. Bent u zeker dat u deze actie wilt uitvoeren?',
                'submit-label' => 'Ja, ik ben zeker',
            ],
        ],
        'edit-action' => [
            'modal' => [
                'heading' => 'Notitie bewerken',
            ],
            'description' => 'Gegevens van een notitie dat gekoppeld is aan het woordenboek artikel bewerken.',
        ],
        'create-action' => [
            'label' => 'Notitie aanmaken',
            'modal' => [
                'description' => 'Toevoegen van een notitie aan het woordenboek artikel.',
            ],
        ],
        'bulk-delete' => [
            'modal' => [
                'heading' => 'Notitie(s) verwijderen',
                'submit-label' => 'Ja, ik ben zeker',
                'description' => 'U staat op het punt om een of meerdere notities te verwijderen. Bent u zeker dat u deze actie wilt uitvoeren?',
            ],
        ],
    ],
    'colums' => [
        'author' => 'Auteur',
        'title' => 'Titel',
        'updated-at' => 'Laatst bewerkt',
        'created-at' => 'Registratie datum',
    ],
];
