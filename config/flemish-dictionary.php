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
];
