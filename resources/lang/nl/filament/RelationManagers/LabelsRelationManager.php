<?php

return [
    'table' => [
        'heading' => 'Gekoppelde labels',
        'description' => 'Overzicht van alle aan het woord gekoppelde labels.',
        'columns' => [
            'name' => 'Naam',
            'description' => 'Beschrijving',
            'description-placeholder' => '- geen beschrijving opgegeven',
            'attached-at' => 'Gekoppeld op',
        ],
    ],
    'empty-state' => [
        'heading' => 'Geen label gevonden',
        'description' => 'Momenteel zijn er geen labels gekoppeld aan het artikel gebruik de bovenstaande knop om een label te koppelen',
    ],
    'actions' => [
        'create' => [
            'modal' => [
                'description' => 'Na het aanmaken van een label zal deze automatisch aan heb woordenboek artikel worden gekoppeld.',
            ],
        ],
        'attach' => [
            'label' => 'Labels koppelen',
        ],
    ],
];
