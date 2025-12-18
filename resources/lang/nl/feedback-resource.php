<?php

return [
    'infolist' => [
        'fieldsets' => [
            'sender' => 'Ingestuurd door',
            'feedback' => 'Feedback'
        ],
        'entries' => [
            'first-time-visit' => 'Eerste bezoek',
            'results-found-easily' => 'Kon gemakkelijk resultaten bekomen',
            'contact-allowed' => 'Mag gecontacteerd worden',
            'visit-reason' => [
                'label' => 'Reden van het bezoek aan het Vlaams woordenboek',
                'placeholder' => '- Niet opgegeven'
            ],
            'search-additional-info' => [
                'label' => 'Wat er volgens de gebruiker beter kon tijdens het zoeken naar artikelen',
                'placeholder' => '- Niet opgegeven'
            ],
            'additional-info' => [
                'label' => 'Extra info / Suggestie(s) van de gebruiker',
                'placeholder' => '- Niet ingevuld'
            ]
        ]
    ],
    'infolist.entries.visit-reason.label' => 'Reden van het bezoek aan het Vlaams Woordenboek',
    'statuses' => [
        'processed' => 'behandeld',
        'unprocessed' => 'onbehandeld'
    ],
    'table' => [
        'heading' => 'Ingezonden feedback',
        'description' => 'Een overzicht van alle feedback of bugs die zijn ingezonden door gebruikers van het Vlaams Woordenboek',
        'empty-state' => [
            'heading' => 'Geen feedback ontvangen',
            'description' => 'Momenteel is er nog geen feedback ingestuurd door gebruikers van het Vlaams woordenboek. Kom later nog eens terug.'
        ],
        'filters' => [
            'status' => [
                'label' => 'Status'
            ]
        ],
        'columns' => [
            'tracking-number' => 'Volgnummer',
            'name' => 'Ingestuurd door',
            'email' => [
                'label' => 'E-mail adres',
                'placeholder' => '- niet opgegeven'
            ],
            'contact-allowed' => 'Contact toegestaan',
            'first-time-visit' => 'Eerste bezoek',
            'results-found-easily' => 'Resultaten gevonden?',
            'created-at' => 'Ingestuurd op'
        ],
        'actions' => [
            'delete-bulk-action' => [
                'modal-description' => 'Bij het verwijderen van de feedback kan het mogelijks zijn dat er waardevolle feedback verloren gaat. Alvorens de feedback te verwijderen wees er zeker van dat de personen die er baat bij hebben de feedback hebben gelezen.'
            ],
            'mark-as-bulk-group' => [
                'label' => 'Markeren als',
                'close-action' => [
                    'label' => 'Behandeld',
                    'notifications' => [
                        'success' => 'De geselecteerde feedback is gemarkeerd als behandeld.'
                    ]
                ],
                'open-action' => [
                    'label' => 'Onbehandeld',
                    'notifications' => [
                        'success' => 'De geselecteerde feedback is gemarkeerd als onbehandeld.'
                    ]
                ]
            ],
            'delete-action' => [
                'tooltip' => 'Verwijderen',
                'modal' => [
                    'description' => 'Bij het verwijderen van de feedback kan het zijn indien de onbehandeld is waardevolel informatie verloren gaat voor de verdere groei van het Vlaams Woordenboek, en vragen we je om deze handeling te bevestigen'
                ]
            ],
            'view-action' => [
                'tooltip' => 'Bekijken',
                'modal' => [
                    'heading' => [
                        'specific' => ':number: Feedback informatie',
                        'general' => 'Feedback overzicht'
                    ],
                    'description' => 'Ingestuurd door :user op :date',
                    'footer-actions' => [
                        'mail' => 'Mail gebruiker'
                    ]
                ]
            ]
        ]
    ],
    'table.actions.delete-action.modal.description' => 'Let op! Als je onbehandelde feedback verwijdert, gaat er mogelijk waardevolle informatie verloren voor het beheer van het Vlaams Woordenboek. Daarom vragen we je om deze handeling extra te bevestigen;',
    'table.actions.delete-bulk-action.modal-description' => 'Bij het verwijderen van de feedback kan er waardevolle feedback verloren gaan. Ga na of de personen die er belang bij hebben de feedback hebben gelezen voor je die verwijdert.',
    'table.actions.mark-as-bulk-group.open-action.notifications.success' => 'De geselecteerde feedback is gemarkeerd als niet behandeld.',
    'table.actions.view-action.modal.heading.general' => 'Feedbackoverzicht',
    'table.actions.view-action.modal.heading.specific' => ':number: Feedbackinformatie',
    'table.columns.email.label' => 'E-mailadres',
    'table.empty-state.description' => 'Er is nog geen feedback ingestuurd door gebruikers van het Vlaams Woordenboek. Kom later nog eens terug.',
    'widgets' => [
        'statistics' => [
            'heading' => ':count rapportering',
            'filters' => [
                'perWeek' => 'Op weekbasis',
                'perMonth' => 'Op maandbasis'
            ],
            'dataset-labels' => [
                'new-visitors' => 'feedback van nieuwe bezoekers',
                'recurring-visitors' => 'feedback van terugkerende bezoekers',
                'all-visitors' => 'feedback van alle type gebruikers'
            ]
        ]
    ],
    'widgets.statistics.dataset-labels.all-visitors' => 'feedback van alle types gebruikers'
];
