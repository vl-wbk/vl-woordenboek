<?php

return [
    'actions' => [
        'view-etymology' => [
            'label' => 'Acties',
            'view-article' => [
                'mark-label' => 'Markeren als',
                'label' => 'Bekijk gekoppeld artikel'
            ]
        ]
    ],
    'bulk-actions' => [
        'delete' => [
            'modal' => [
                'heading' => 'Etymologische gegevens verwijderen',
                'description' => 'U staat op het punt om etymologische gegevens te verwijderen. Ben u zeker deze actie te willen uitvoeren?',
                'submit-label' => 'Ja, ik ben zeker'
            ]
        ]
    ],
    'bulk-actions.delete.modal.description' => 'Je staat op het punt om etymologische gegevens te verwijderen. Ben je er zeker van dat je deze actie wil uitvoeren?',
    'bulk-actions.delete.modal.submit-label' => 'Ja, ik ben zeker.',
    'custom-actions' => [
        'draft' => [
            'modal' => [
                'heading' => 'Gegevens in onderhoud plaatsen',
                'description' => 'U staat op het punt om de etymologische gegevens in onderhoud te plaatsen. In deze fase zullen de gegevens niet publiekelijk raadpleegbaar zijn. Bent u zeker dat u dit wilt doen?',
                'submit-label' => 'ja, ik ben zeker'
            ],
            'notifications' => [
                'success-title' => 'De etymologische gegevens zijn nu in onderhoud',
                'failure-title' => 'Helaas pindakaas! Er is iets misgelopen.'
            ]
        ],
        'reject' => [
            'modal' => [
                'heading' => 'Etymologie afwijzen',
                'description' => 'U staat op het punt om een etymology af te wijzen in het systeem. Bij afwijzing zal deze niet gepubliceerd worden. Bent u zeker dat u dit wilt doen?',
                'submit-label' => 'Ja, ik ben zeker'
            ],
            'form' => [
                'label' => 'Reden van de afwijzing',
                'placeholder' => 'Beschrijf kort waarom je de gegevens wilt afwijzen.'
            ],
            'notifications' => [
                'success-title' => 'De etymologische gegevens of bijdragen zijn afgewezen',
                'failure-title' => 'Helaas pindakaas! Er is iets misgelopen.'
            ]
        ],
        'publish' => [
            'modal' => [
                'heading' => 'Etymologie publiceren',
                'description' => 'U staat op het punt om meen etymologie beschikbaar te stellen voor het brede publiek.Weet u zeker dat u dit wilt doen?',
                'submit-label' => 'Ja, ik weet dit zeker'
            ],
            'notifications' => [
                'success-title' => 'De etymologische gegevens zijn gepubliceerd.',
                'failure-title' => 'Helaas pindakaas! Er is iets misgelopen.'
            ]
        ],
        'under-review' => [
            'modal' => [
                'heading' => 'Etymologie in review plaatsen',
                'description' => 'Bij het plaatsen van de etymologie in review. Zal deze ingezonden worden ter beoordeling. Onder deze status zal het niet meer mogelijk zijn om de etymologie te bewerken.',
                'submit-label' => 'Insturen'
            ],
            'notifications' => [
                'success-title' => 'De etymologie is ingestuurd ter beoordeling',
                'failure-title' => 'Helaas pindakaas! Er is iets misgelopen.'
            ]
        ],
        'archive' => [
            'modal' => [
                'heading' => 'Etymologie archiveren',
                'description' => 'U staat op het punt om etymologische gegevens te archiveren. Bent u zeker dat u deze handeling wilt uitvoeren?',
                'submit-label' => 'Ja, ik ben zeker',
                'form' => [
                    'label' => 'Reden van de archivering',
                    'placeholder' => 'Beschrijf kort waarom je de gegevens wilt archiveren.'
                ]
            ],
            'notifications' => [
                'success-title' => 'De gegevens zijn gearchiveerd',
                'failure-title' => 'Helaas pindakaas! Er is iets misgelopen.'
            ]
        ]
    ],
    'custom-actions.archive.modal.description' => 'Je staat op het punt om etymologische gegevens te archiveren. Ben je er zeker van dat je dit wil doen?',
    'custom-actions.archive.modal.form.placeholder' => 'Beschrijf kort waarom je de gegevens wil archiveren.',
    'custom-actions.archive.notifications.failure-title' => 'Er is helaas iets misgelopen.',
    'custom-actions.draft.modal.description' => 'Je staat op het punt om de etymologische gegevens in onderhoud te plaatsen. In deze fase zullen de gegevens niet publiekelijk raadpleegbaar zijn. Ben je er zeker van dat je dit wil doen?',
    'custom-actions.draft.modal.submit-label' => 'ja, ik ben zeker.',
    'custom-actions.draft.notifications.failure-title' => ' Er is helaas iets misgelopen.',
    'custom-actions.publish.modal.description' => 'Je staat op het punt om een etymologie beschikbaar te stellen voor het brede publiek.Weet je zeker dat je dit wil doen?',
    'custom-actions.publish.notifications.failure-title' => 'Er is helaas iets misgelopen.',
    'custom-actions.reject.form.placeholder' => 'Beschrijf kort waarom je de gegevens wil afwijzen.',
    'custom-actions.reject.modal.description' => 'Je staat op het punt om een etymologie  in het systeem af te wijzen. Bij afwijzing zal die niet gepubliceerd worden. Ben je er zeker van dat je dit wil doen?',
    'custom-actions.reject.modal.submit-label' => 'Ja, ik ben zeker.',
    'custom-actions.reject.notifications.failure-title' => 'Er is helaas iets misgelopen.',
    'custom-actions.under-review.modal.description' => 'Bij het plaatsen van de etymologie in review zal die ter beoordeling worden ingezonden. Onder deze status kan de etymologie niet meer bewerkt worden.',
    'custom-actions.under-review.notifications.failure-title' => 'Er is helaas iets misgelopen.',
    'form' => [
        'fields' => [
            'status' => 'Status van de gegevens',
            'origin-period' => 'Periode',
            'origin' => [
                'label' => 'Ontleend uit (taal + oorspr. vorm + betekenis)',
                'placeholder' => 'Bijv. Latijn \'gustus\', smaak'
            ],
            'source' => [
                'name' => [
                    'label' => 'Naam van de bron (bijv. WNT, Etymologiebank)'
                ],
                'hyperlink' => [
                    'label' => 'Link naar de bron',
                    'placeholder' => 'Bijv. https://etymologiebank.nl/trefwoord/goesting'
                ]
            ],
            'oldest-find' => [
                'period' => 'Periode / Jaartal',
                'spot' => [
                    'label' => 'Oudste vindplaats in het Nederlands (vorm, context, evt. betekenis)',
                    'placeholder' => 'Bijv. goeste, in \'lot may men goeste vray.\' Huygens.'
                ]
            ],
            'further-development' => [
                'label' => 'Verdere ontwikkelingen (talen + vorm + betekenis)',
                'placeholder' => 'Bijv. Oudfrans \'gost\'; Middelfrans \'goust\', smaak',
                'period' => [
                    'label' => 'Periodes',
                    'placeholder' => '12de, 13de eeuw'
                ]
            ],
            'etymology' => [
                'label' => 'Etymologie',
                'placeholder' => 'Bijv. ontleend aan het Oudfranse \'gost\', smaak (12de eeuw), gevormd met het achtervoegsel -ing. \'Gost\' komt op zijn beurt uit het Latijn \'gustus\', smaal. Oorsponkelijk \'goest(e)\'.'
            ]
        ]
    ],
    'header-actions' => [
        'view-user' => [
            'label' => 'Bekijk gebruiker'
        ],
        'create' => [
            'label' => 'Gegevens toevoegen',
            'modal' => [
                'heading' => 'Etymologische gegevens toevoegen',
                'description' => 'U staat op het punt om etymologische gegevens toe te voegen voor het woord :word'
            ]
        ]
    ],
    'header-actions.create.modal.description' => 'Je staat op het punt om etymologische gegevens toe te voegen bij het woord :word',
    'infolist' => [
        'heading' => 'Gegevens van de auteur en registratie',
        'description' => 'Alle gegevens omtrent de persoon die de etymologie heeft aangemaakt in het Vlaams woordenboek',
        'general-information-tab' => [
            'label' => 'Algemene informatie',
            'tooltip' => [
                'archived' => 'Gearchiveerd door :user op :time',
                'rejected' => 'Afgewezen door :user op :time',
                'published' => 'Gepubliceerd door :user op :time'
            ],
            'entries' => [
                'status' => 'Status',
                'origin' => 'Ontleend uit het:',
                'origin-period' => 'Periode',
                'further-development' => 'Verdere ontwikkelingen (talen + vorm + betekenis)',
                'further-development-period' => 'Periode / Jaartal',
                'additional-info' => 'Aanvullende informatie'
            ]
        ],
        'rejection-information-tab' => [
            'entries' => [
                'rejecter-name' => 'Afgewezen door',
                'rejection-timestamp' => 'Tijdstip van afwijzing',
                'reason' => [
                    'label' => 'Beweegredenen tot de afwijzing',
                    'placeholder' => '- Geen redenen opgegeven van de afwijzing voor de record'
                ]
            ]
        ],
        'archive-information-tab' => [
            'label' => 'Archiverings informatie',
            'entries' => [
                'archiver' => 'Gearchiveerd door',
                'timestamp' => 'Tijdstip van archivering',
                'reason' => [
                    'label' => 'Beweegredenen tot de archivering',
                    'placeholder' => '- Geen redenen opgegeven van de archivering van de record'
                ]
            ]
        ],
        'source-information-tab' => [
            'label' => 'Bron gegevens',
            'entries' => [
                'oldest-find-spot' => 'Oudste vindplaats in het Nederlands',
                'oldest-find-period' => 'Vondst (Periode / Jaartal)',
                'source-name' => 'Naam van de bron',
                'source-hyperlink' => [
                    'label' => 'Link naar de bron',
                    'placeholder' => '- geen hyperlink opgegeven',
                    'url' => '- geen valide link opgegeven'
                ]
            ]
        ],
        'author-information-tab' => [
            'entries' => [
                'name' => 'Naam',
                'email' => 'E-mail adres',
                'created-at' => 'Etymologie ingediend op',
                'edited-at' => 'Laatste wijziging (etymologie)'
            ]
        ]
    ],
    'infolist.archive-information-tab.entries.reason.label' => 'Reden(en) voor de archivering',
    'infolist.archive-information-tab.entries.reason.placeholder' => '- Geen reden opgegeven voor de archivering van het record',
    'infolist.archive-information-tab.label' => 'Archiveringsinformatie',
    'infolist.author-information-tab.entries.email' => 'E-mailadres',
    'infolist.description' => 'Alle gegevens omtrent de persoon die de etymologie heeft aangemaakt in het Vlaams Woordenboek',
    'infolist.rejection-information-tab.entries.reason.placeholder' => '- Geen redenen opgegeven voor de afwijzing van het record',
    'infolist.source-information-tab.entries.oldest-find-period' => 'Periode / jaartal attestatie',
    'infolist.source-information-tab.label' => 'Brongegevens',
    'table' => [
        'heading' => 'Etymologie overzicht',
        'description' => 'Overzicht van alle etymologieën die geregistreerd staan in het Vlaams woordenboek.',
        'empty-state' => [
            'heading' => 'Geen Etymologieën gevonden',
            'description' => 'Het lijkt erop dat er momenteel etymologieën gevonden zijn onder de matchende criteria.'
        ],
        'columns' => [
            'author-name' => 'Ingevoegd door',
            'connected-article' => 'Gekoppeld artikel',
            'status' => 'Status',
            'origin' => 'Oorsprong',
            'origin-period' => 'Oorsprong periode',
            'created-at' => 'Aangemaakt op',
            'updated-at' => 'Laatst gewijzigd'
        ],
        'filters' => [
            'status' => [
                'label' => 'Status'
            ]
        ],
        'actions' => [
            'delete' => [
                'modal' => [
                    'heading' => 'Etymologische gegevens verwijderen'
                ]
            ]
        ]
    ],
    'table.columns.origin-period' => 'Periode oorsprong',
    'table.description' => 'Overzicht van alle etymologieën die geregistreerd staan in het Vlaams Woordenboek.',
    'table.empty-state.description' => 'Er zijn geen etymologieën gevonden volgens de gevraagde criteria.',
    'table.empty-state.heading' => 'Geen etymologieën gevonden',
    'table.heading' => 'Etymologie-overzicht'
];
