<?php

/**
 * This file contains all the translation strings used for authorization messages and resource names within the application.
 * It's used by developers to display messages and by translators to provide the correct phrasing in different languages.
 */
return [
    /**
     * This section defines the names of various 'resources' (things) within the application.
     * For example, a 'category', 'user', or 'product'.
     *
     * The 'key' (left side, e.g., 'category') is the internal name used by the system.
     * The 'value' (right side, e.g., 'category') is the actual word that will be displayed to users.
     */
    'resources' => [
        'ban' => 'Deactivatie',
        'bans' => 'Deactivaties',
        'category' => 'categorie',
        'categories' => 'categorieen',
        'blogPost' => 'Nieuwsbericht',
        'blogPosts' => 'Nieuwsberichten',
        'comment' => 'reactie',
        'export' => 'export',
        'disclaimer' => 'disclaimer',
        'disclaimers' => 'disclaimers',
        'article' => 'Artikel',
        'articles' => 'Artikelen',
    ],

    /**
     * This section contains all translation messages related to 'policies' or 'permissions'.
     * These messages are typically shown to users when they try to do something they are not allowed to.
     */
    'policies' => [
        /**
         * These messages are displayed when a user is denied access or permission to perform an action.
         *
         * IMPORTANT FOR TRANSLATORS:
         *
         * You will see placeholders like ':resource' and ':resources' in these messages.
         * These are special markers that the system will automatically replace with the
         * correct resource name (e.g., 'category', 'user') from the 'resources' section above.
         *
         * - ':resource': Will be replaced by a singular resource name (e.g., "category").
         * - ':resources': Will be replaced by a plural resource name (e.g., "categories").
         *
         * Please ensure these placeholders remain exactly as they are (including the colon ':') in your translations, as the system needs them to function correctly.
         */
        'responses' => [
            'deny_before_message' => 'U hebt geen globale machtiging voor het systeem dat :resource behandeld',
            'deny_view_any_message' => 'U hebt geen toestemming om het overzicht van :resource te bekijken',
            'deny_view_message' => 'U hebt geen toestemming om de informatie van :resource te bekijken',
            'deny_create_message' => 'U hebt geen toestemming om een nieuwe :resource aan te maken',
            'deny_update_message' => 'U hebt geen toestemming om de gegevens van een :resource aan te passen',
            'deny_delete_message' => 'U hebt geen toestemming op een :resource te verwijderen',
            'deny_delete_any_message' => 'U hebt geen toestemming om meerdere :resource te verwijderen',
            'deny_undo_publication_message' => 'U hebt geen toestemming om de publicatie van :resource ongedaan te maken',
            'deny_publication_message' => 'U hebt geen toestemming om een :resource te publiceren',
            'deny_create_comment_message' => 'Momenteel kun je niet reageren op dit :resource, of is je email adres niet geverifieerd.',
            'deny_submit_post_message' => 'U kunt helaas geen artikelen aanleveren ter publicatie omdat je email adres niet geverifieerd is.'
        ],
    ],
];
