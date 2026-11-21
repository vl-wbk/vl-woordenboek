<?php

return [
    /**
     * Configuration settings for dictionary articles and their lifecycle.
     * This includes thresholds for editor activity, article freezing and state-based visibility.
     */
    'articles' => [
        /**
         * The number of days of inactivity required before a dictionary article or editor is considered "frozen".
         */
        'frozen-threshold' => 14,
    ],

    /**
     * Rate Limiting Configuration
     *
     * This configuration defines throttling profiles for various form submissions
     * across the application. Each profile specifies limits for both guests
     * and authenticated members.
     *
     * Profiles:
     * - 'default': Standard throttling for general actions (e.g., search, comments).
     * - 'contact_form': Strict throttling for high-value/sensitive mail actions.
     *
     * Options:
     * - guest_limit:  (int) Max attempts allowed for unauthenticated users (via IP).
     * - member_limit: (int) Max attempts allowed for logged-in users (via User ID).
     * - decay_seconds: (int) The time window (in seconds) before the limit resets.
     */
    'rate-limiting' => [
        'default' => [
            'guest_limit'  => 4,
            'member_limit' => 12,
            'decay_seconds' => 300,
        ],

        'feedback' => [
            'guest_limit'  => 4,
            'member_limit' => 10,
            'decay_seconds' => 3600, // Throttled to a 1-hour window
        ],

        'suggestions' => [
            'anonymous' => [
                'max' => 5,
                'window' => 24,
            ],

            'authenticated' => [
                'max' => 70,
                'window' => 24 * 7, // Weeky based quota
            ],

            // Burst protection (separate layer)
            'burst' => [
                'anonymous' => '5,1',
                'logged_in' => '20,1',
            ],

        ],
    ],

    /**
     * features that can be banned per suer from Filament.
     * key => label shown in the admin panel.
     * Every key is registered in Pennant by App\Providers\FeatureServiceProvider.
     * Use the same key in middleware, Blade (@feature) and Feature::active().
     */
    'features' => [
        'bannable' => [
            'community-examples' => 'Voorbeeldzinnen',          // TODO: Implementation
            'community-articles' => 'Community artikelen',      // TODO: Implementation
            'corrections' => 'Correcties',                      // TODO: Implementation
            'article-reports' => 'Artikel rapporteringen',      // TODO: Implementation
            'voting-feedback' => 'Stemmingsfeedback',
        ],
    ],

    'environments' => [
        'staging' => [
            'repository' => env('GITHUB_REPOSITORY'),
            'issues' => env('GITHUB_REPOSITORY') . '/issues/new/choose'
        ],
    ],


    /**
     * --------------------------------------------------------------------------
     * Buy Me a Coffee
     * --------------------------------------------------------------------------
     *
     * Settings for the "Steun het Vlaams Woordenboek" button that is shown in
     * the voting card (resources/views/livewire/like-words.blade.php).
     *
     * enabled
     *   Master switch. When false, the button is hidden everywhere,
     *   regardless of the hyperlink.
     *
     * hyperlink
     *   The full URL of your Buy Me a Coffee page, for example
     *   "https://www.buymeacoffee.com/your-username". The button is only
     *   rendered when this value is non-empty, so leave it empty (or null)
     *   until you have a real page. Note that the placeholder "#" counts as
     *   non-empty and would show a button that goes nowhere.
     *
     */
    'buy-me-a-coffee' => [
        'enabled' => env('BUY_ME_A_COFFEE_ENABLED', false),
        'hyperlink' => env('BUY_ME_A_COFFEE_URL'),
    ],
];
