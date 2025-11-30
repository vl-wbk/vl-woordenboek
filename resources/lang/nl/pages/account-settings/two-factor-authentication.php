<?php

return [
    'title' => 'Two factor authenticatie',
    'subtitle' => 'Voeg een extra laag beveiliging toe aan je account door middel van Two factor authenticatie.',
    'status' => [
        'active' => 'Two factor authenticatie is ingesteld en <span class="fw-bold">actief</span> op je account. Geen verdere handelingen zijn nodig. Gebruik de knoppen onderaan om codes te genereren of de functie te deactiveren.',
        'inactive-text' => 'Two-factor authenticatie biedt een extra beveiligingslaag tegen ongeautoriseerde toegang. Bij het inloggen wordt een unieke token gevraagd die via een app op uw telefoon wordt gegenereerd.',
    ],
    'buttons' => [
        'activate' => 'Activeer Two Factor Authenticatie',
        'regenerate-codes' => 'Genereer herstelcodes',
        'deactivate' => 'Deactiveer Two Factor Authenticatie',
        'confirm' => 'Bevestigen',
        'copy-codes' => 'Kopieer codes',
        'download-codes' => 'Download codes',
    ],
    'confirmation-step' => [
        'title' => '3. Bevestig de authenticatie',
        'text' => 'Voer de 6-cijferige code van je authenticator app in ter controle om de installatie te voltooien.',
    ],
    'recovery-code-step' => [
        'title' => '2. Bewaar de herstelcode',
        'text' => 'Sla de volgende <strong>herstelcodes</strong> veilig op. Deze zijn cruciaal om weer toegang te krijgen als je je telefoon verliest.',
    ],
    'recovery-codes' => [
        'title' => 'Nieuwe herstelcodes zijn aangemaakt!',
        'text' => 'Sla deze <strong>nieuwe codes</strong> onmiddellijk op in een password manager of op een andere veilige locatie. De vorige codes zijn nu ongeldig.',
    ],
    'setup' => [
        'title' => 'Installatie in 3 Stappen',
        'info' => 'Volg de stappen hieronder om de Two-Factor Authenticatie te voltooien en te bevestigen.',
        'key' => 'Handmatige sleutel: :key',
    ],
    'scan-step' => [
        'title' => '1. Scan de QR-code',
        'text' => 'Scan de onderstaande QR code met je Authenticator App (zoals Google Authenticator of Authy) op je smartphone.',
    ],
];
