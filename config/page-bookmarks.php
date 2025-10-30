<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pages Bookmarks Configuration
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    |
    | Here you can customize the table names used by the page bookmarks package.
    | You can change these if you need to avoid conflicts with existing tables
    | or prefer different naming conventions.
    |
    */
    'tables' => [
        'bookmarks' => 'backend_bookmarks',
        'bookmark_folders' => 'backend_bookmark_folders',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | This is the model that will be used to associate bookmarks with users.
    | You can change this to your own User model if you have a custom one.
    |
    */
    'models' => [
        'user' => \App\Models\User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how the bookmark modals should be displayed.
    | Options: 'modal' or 'slideOver'
    | Default: 'slideOver' for both
    |
    */
    'modal' => [
        'add_bookmark' => 'slideOver', // 'modal' or 'modal'
        'view_bookmarks' => 'slideOver', // 'modal' or 'slideOver'
    ],

    /*
    |--------------------------------------------------------------------------
    | Icons Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the icons used throughout the page bookmarks interface.
    | You can use any Heroicon or custom icon names that are available
    | in your Filament installation.
    |
    */
    'icons' => [
        'add_bookmark' => 'heroicon-o-folder-plus',
        'view_bookmarks' => 'heroicon-o-bookmark',
        'bookmark_item' => 'heroicon-o-bookmark',
        'folder' => 'heroicon-o-folder',
        'search' => 'heroicon-o-magnifying-glass',
        'delete' => 'heroicon-o-trash',
        'chevron_down' => 'heroicon-o-chevron-down',
        'empty_state' => 'heroicon-o-bookmark',
    ],

    /*
    |--------------------------------------------------------------------------
    | Render Hooks Configuration
    |--------------------------------------------------------------------------
    |
    | Configure where the bookmark manager and viewer components should be
    | rendered in the Filament panel. You can use any of the available
    | PanelsRenderHook constants from Filament\View\PanelsRenderHook.
    */
    'render_hooks' => [
        'add_bookmark' => \Filament\View\PanelsRenderHook::GLOBAL_SEARCH_AFTER,
        'view_bookmarks' => \Filament\View\PanelsRenderHook::GLOBAL_SEARCH_AFTER,
    ],
];
