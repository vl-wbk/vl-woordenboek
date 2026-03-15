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
    ],
];
