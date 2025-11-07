<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users;

use App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers\ReportsRelationManager;
use App\Filament\Clusters\UserManagement\Resources\RoleResource\RelationManagers;
use App\Filament\Clusters\UserManagement\Resources\UserResource\RelationManagers\SuggestionsRelationManager;
use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Resources\Users\Schema as UserSchema;
use App\Filament\Resources\Users\Widgets\UserRegistrationChartWidget;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\User;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;

/**
 * Welcome to the User Management System of our Flemish Dictionary
 *
 * Picture this as your control room for everything user-related in our application.
 * Here, administrators can oversee all user accounts, from creation to retirement.
 * We've designed this system with our Flemish administrators in mind, so you'll find everything written in Dutch, making it feel natural and intuitive.
 *
 * When you're working here, you can create new user accounts, modify existing ones, and manage access levels.
 * Think of it as a complete toolkit for user management.
 * Need to add a new moderator? You can do that. Want to check when someone last logged in? That information is right at your fingertips.
 *
 * Security was a top priority in our design. Every sensitive action requires proper permissions, and we keep detailed logs of important changes.
 * If someone tries something they shouldn't, our system will politely decline. We've also built in tools for account suspension, just in case they're needed.
 */
final class UserResource extends Resource
{
    use HasActiveIcon;

    /**
     * At the core of our system sits this connection to the User model.
     *
     * Every time you create a new account, update someone's email, or make any other user-related changes, this line of code makes it happen.
     * Think of it as the bridge between what you see on screen and where the data lives in our database.
     */
    protected static ?string $model = User::class;

    /**
     * Throughout our interface, when we talk about multiple users, we say "gebruikers".
     * You'll see this word in headers, navigation menus, and messages.
     * For example, when you're looking at the user list, you might see "25 gebruikers gevonden" or "Gebruikers beheren".
     */
    protected static ?string $pluralModelLabel = 'gebruikers';

    /**
     * When referring to just one user, we use "gebruiker".
     * This appears in messages like "Gebruiker toevoegen" or "Gebruiker bijwerken". Keeping everything in
     * Dutch helps our administrators feel at home in the interface.
     */
    protected static ?string $modelLabel = 'gebruiker';

    /**
     * In the navigation menu, we use a simple users icon to mark this section.
     * We chose this particular icon because it's universally recognized and immediately tells administrators they're in the user management area.
     */
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    /**
     * Organization matters, so we've grouped all user-related tools together under the UserManagement cluster.
     * This keeps things tidy and makes sure administrators can find everything they need in one place.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = UserManagementCluster::class;

    /**
     * Our form builder - where we craft the perfect user creation experience.
     *
     * When administrators need to add or edit users, this is where the magic happens.
     * We've designed a clean, intuitive form that guides them through the process step by step.
     * The form is arranged in a 12-column grid layout, making efficient use of the available space.
     *
     * The form includes essential fields for user management:
     * First, administrators select a user group - this determines what the user can do.
     * Then they enter personal details like first name and last name.
     * Finally, there's the email field, which we carefully validate to ensure uniqueness.
     *
     * Everything is labeled in Dutch, maintaining our commitment to a fully localized interface.
     *
     * @param  Schema  $schema  The Filament form builder instance
     * @return Schema           The configured form ready for display
     */
    public static function form(Schema $schema): Schema
    {
        return UserSchema\UserForm::configure($schema);
    }

    /**
     * Configures the "record view" panel for a single user.
     *
     * The infolist page provides a structured read-only overview for a user's account details.
     * This includes:
     *
     * - Personal information
     * - Account status and verification indicators
     * - Creation, modification, and login timestamps
     *
     * The layout and component definitions are maintained externally in the
     * `UserInfolist` schema so that visual and structural changes can be shared
     * across multiple resources if needed.
     *
     * This page is typically accessed from the index table using a "view" action.
 *
     * @param  Schema $schema The Filament infolist builder instance.
     * @return Schema         Configured schema ready for rendering
     */
    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return UserSchema\UserInfolist::configure($schema);
    }

    /**
     * Registers dashboard widgets related to user insights.
     *
     * These widgets appear above the resource's index table, giving administrators quick access
     * to key user analytics such as:
     *
     * - Total new users over time
     * - Registration trends and growth indicators
     *
     * The widget area can be extended by third-party modules or custom development
     * simply by pushing additional widget classes into this array.
     *
     * @return array<class-string>
     */
    public static function getWidgets(): array
    {
        return [UserRegistrationChartWidget::class];
    }

    /**
     * Declares the relationship managers that can be accessed from this resource.
     *
     * These provide administratively useful insight and control over associated
     * data that a user interacts with in the platform, including:
     *
     * - Suggestions they’ve submitted to improve the dictionary
     * - Reports they’ve filed (e.g. flagged terms)
     * - Roles that define their permissions and access control
     *
     * Each relation manager routes to its respective CRUD interface, and all
     * visibility and access control follows Filament’s authorization system.
     *
     * @return array<class-string>
     */
    public static function getRelations(): array
    {
        return [
            SuggestionsRelationManager::class,
            ReportsRelationManager::class,
            RelationManagers\RolesRelationManager::class,
        ];
    }

    /**
     * Our user overview table - the command center for user management.
     *
     * This table is where administrators spend most of their time. It presents user information in a clear, organized way with powerful management tools, right at their fingertips.
     *
     * The table shows crucial information about each user:
     * Their name appears first, with a special indicator if their account is banned.
     * Their role is displayed as a neat badge for quick identification.
     * The email address is clickable, opening their default email client.
     * We also show when they last logged in and when they first registered.
     *
     * Security is built right in - actions like banning users are only visible
     * to administrators with the right permissions.
     *
     * @param  Table  $table  The Filament table builder instance
     * @return Table          The fully configured table ready for display
     */
    public static function table(Table $table): Table
    {
        return UserSchema\UserTable::configure($table);
    }

    /**
     * The navigation map of our user management system.
     *
     * This method sets up the different pages administrators can visit
     * while managing users. Think of it as drawing the paths through our user management area.
     *
     * We have three main destinations:
     *
     * The index page shows the overview of all users.
     * The create page is where new users are born.
     * The edit page is where existing user details can be modified.
     *
     * Each route is carefully named in Dutch, matching our interface language.
     *
     * @return array<string, PageRegistration> The route definitions for user management
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
