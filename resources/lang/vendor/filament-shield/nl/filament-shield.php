<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => 'Naam',
    'column.guard_name' => 'Guard Naam',
    'column.roles' => 'Rollen',
    'column.permissions' => 'Permissies',
    'column.updated_at' => 'Aangepast op',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Naam',
    'field.guard_name' => 'Guard Naam',
    'field.permissions' => 'Permissies',
    'field.select_all.name' => 'Selecteer alles',
    'field.select_all.message' => 'Zet alle permissies aan, die momenteel <span class="text-primary font-medium">aangevinkt</span> staan voor deze rol.',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Filament Shield',
    'nav.role.label' => 'Rollen',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Rol',
    'resource.label.roles' => 'Rollen',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Entiteiten',
    'resources' => 'Resources',
    'widgets' => 'Widgets',
    'pages' => 'Pagina\'s',
    'custom' => 'Andere permissies',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'Je hebt geen toegang',

    /*
    |--------------------------------------------------------------------------
    | Resource Permissions' Labels
    |--------------------------------------------------------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => 'Bekijken',
        'view_any' => 'Bekijk elke',
        'create' => 'Aanmaken',
        'update' => 'Bewerken',
        'delete' => 'Verwijderen',
        'delete_any' => 'Verwijderen van meerdere',
        'force_delete' => 'Forceer verwijderen',
        'force_delete_any' => 'Geforceerd verwijderen van meerdere',
        'restore' => 'Verwijdering ongedaan maken',
        'restore_any' => 'Herstellen van meerdere',
        'replicate' => 'Repliceren',
        // 'reorder' => 'Reorder',

        // Custom prefix for permissions
        'detach_editor' => 'Redacteur loskoppelen',
        'unarchive' => 'Dearchiveren',
        'send_for_approval' => 'Inzenden ter goedkeuring',
        'export' => 'Exporteren van gegevens',
        'publish' => 'Publiceren',
        'detach_disclaimer' => 'Disclaimer loskoppelen',
        'unpublish' => 'Publicatie ongedaan maken',
        'archive' => 'Archiveren',
        'mark_in_progress' => 'Markeren als in behandeling',
        'mark_as_closed' => 'Markeren als gesloten',
        'undo_publication' => 'Publicatie ongedaan maken',
        'reject' => 'Afwijzen',
        'draft' => 'Markeren als klad versie',
        'under_review' => 'Ter controle aanbieden',
        'attach' => 'Koppelen',
        'detach' => 'Loskoppelen',
        'reactivate' => 'Reactivatie van accounts',
        'deactivate' => 'Accounts deactiveren',
        'deactivate_update' => 'Deactivatie gegevens aanpassen',
        'unlock_resource' => 'Resources deblokkeren',
        'change_status' => 'Status aanpassen'
    ],
];
