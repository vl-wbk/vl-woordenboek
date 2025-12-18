<?php

return [
    'actions' => [
        'attach' => [
            'label' => 'Disclaimer koppelen',
            'modal' => [
                'description' => 'Hieronder kunt u de disclaimer selecteren die u wenst te koppelen aan het artikel.',
                'form' => [
                    'select-disclaimer' => 'disclaimer'
                ]
            ]
        ],
        'detach' => [
            'label' => 'Disclaimer loskoppelen'
        ]
    ],
    'actions.attach.modal.description' => 'Hieronder kun je de disclaimer selecteren die je aan het artikel wenst te koppelen.',
    'form' => [
        'sections' => [
            'disclaimer-info' => [
                'title' => 'Disclaimer informatie',
                'description' => 'Alle gegevens en configuratie die gebruikt zal worden om de disclaimer te tonen aan de eindgebruiker die het Vlaams woordenboek raadpleegt.',
                'fields' => [
                    'message' => [
                        'label' => 'Disclaimer melding',
                        'placeholder' => 'Vermeld kort wat je wenst te vermelding richting de gebruiker'
                    ]
                ]
            ],
            'management-info' => [
                'title' => 'Beheersinformatie',
                'description' => 'De nodige registraties van interne gegevens die ons toelaat de disclaimers te beheren en te vermelden hoe we de geregistreerde disclaimer wensen te gebruiken.',
                'fields' => [
                    'name' => 'Naam',
                    'description' => [
                        'label' => 'Beschrijving',
                        'placeholder' => 'Beschrijf kort waarover de disclaimer gaat zodat het duidelijk is voor andere vrijwilligers'
                    ],
                    'usage' => [
                        'placeholder' => 'Beschrijf kort in welke omstandigheden de disclaimer te gebruiken is',
                        'label' => 'Gebruikscriteria'
                    ]
                ]
            ]
        ]
    ],
    'form.sections.disclaimer-info.description' => 'Alle gegevens en de configuratie die gebruikt zal worden om de disclaimer te tonen aan de eindgebruiker die het Vlaams Woordenboek raadpleegt.',
    'form.sections.disclaimer-info.fields.message.label' => 'Disclaimermelding',
    'form.sections.disclaimer-info.fields.message.placeholder' => 'Vermeld kort wat je wenst te vermelden voor de gebruiker.',
    'form.sections.disclaimer-info.title' => 'Disclaimerinformatie',
    'form.sections.management-info.description' => 'De nodige registraties van interne gegevens waarmee we de disclaimers kunnen beheren en kunnen aangeven hoe we de geregistreerde disclaimer willen gebruiken.',
    'form.sections.management-info.fields.usage.placeholder' => 'Beschrijf kort in welke omstandigheden de disclaimer gebruikt moet worden.',
    'header-actions' => [
        'create' => [
            'label' => 'Disclaimer aanmaken'
        ]
    ],
    'infolist' => [
        'disclaimer-info-tab' => [
            'label' => 'Disclaimer informatie',
            'entries' => [
                'type' => 'Disclaimer type',
                'message' => 'Melding'
            ]
        ],
        'internal-description-tab' => [
            'label' => 'Interne beschrijving'
        ],
        'usage-guideline-tab' => [
            'label' => 'Gebruiksrichtlijn'
        ]
    ],
    'infolist.disclaimer-info-tab.entries.type' => 'Disclaimertype',
    'infolist.disclaimer-info-tab.label' => 'Disclaimerinformatie',
    'policy' => [
        'deny-messages' => [
            'before' => 'U hebt geen machtiging om het systeem dat de artikelen beheerd te gebruiken.',
            'viewAny' => 'U hebt geen machtiging om een overzicht van disclaimers te bekijken',
            'view' => 'U hebt geen machtiging om de informatie van een disclaimer te bekijken',
            'create' => 'U hebt geen machtiging om een disclaimer aan te maken',
            'update' => 'U hebt geen machtiging om een disclaimer aan te passen',
            'delete' => 'U hebt geen machtiging om een disclaimer te verwijderen',
            'deleteAny' => 'U hebt geen machtiging om meerdere disclaimers te verwijderen'
        ]
    ],
    'policy.deny-messages.before' => 'Je hebt niet de juiste machtiging om het systeem dat de artikelen beheert te gebruiken.',
    'policy.deny-messages.create' => 'Je hebt niet de juiste machtiging om een disclaimer aan te maken.',
    'policy.deny-messages.delete' => 'Je hebt niet de juiste machtiging om een disclaimer te verwijderen.',
    'policy.deny-messages.deleteAny' => 'Je hebt niet de juiste machtiging om meerdere disclaimers te verwijderen.',
    'policy.deny-messages.update' => 'Je hebt niet de juiste machtiging om een disclaimer aan te passen.',
    'policy.deny-messages.view' => 'Je hebt niet de juiste machtiging om de informatie van een disclaimer te bekijken.',
    'policy.deny-messages.viewAny' => 'Je hebt niet de juiste machtiging om een overzicht van disclaimers te bekijken.',
    'status-labels' => [
        'warning' => 'Waarschuwing disclaimer',
        'default' => 'Standaard disclaimer',
        'danger' => 'Gevaren disclaimer'
    ],
    'status-labels.danger' => 'Gevarendisclaimer',
    'status-labels.warning' => 'Disclaimer waarschuwing',
    'table' => [
        'heading' => 'Disclaimers',
        'description' => 'Disclaimers zijn bedoeld om gebruikers snel extra informatie te geven.',
        'empty-state' => [
            'heading' => 'Geen disclaimer(s) aangemaakt',
            'description' => 'Momenteel zijn er geen disclaimers aangemaakt en of gevonden onder de matchende de gegeven criteria.'
        ],
        'columns' => [
            'name' => 'naam',
            'article-count' => 'aantal koppelingen',
            'description' => 'beschrijving',
            'created-at' => 'aangemaakt op'
        ],
        'actions' => [
            'view-action' => [
                'label' => 'bekijken'
            ],
            'edit-action' => [
                'label' => 'bewerken'
            ],
            'delete-action' => [
                'label' => 'verwijderen',
                'modal' => [
                    'description' => 'U staat op het punt om een disclaimer te verwijderen. Bij het verwijderen zal deze worden losgekoppeld van alle artikelen. Weet u zeker dat je dit wilt doen?'
                ]
            ]
        ]
    ],
    'table.actions.delete-action.modal.description' => 'Je staat op het punt om een disclaimer te verwijderen. Tijdens het verwijderen zal die ook worden losgekoppeld van alle artikelen. Weet je zeker dat je dit wil doen?',
    'table.empty-state.description' => 'Momenteel zijn er geen disclaimers gevonden die voldoen aan de gegeven criteria.'
];
