<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Clusters\UserManagement;
use App\Filament\Clusters\UserManagement\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\Actions;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Schema\InfolistSchema;
use App\Filament\Resources\UserResource\Widgets\UserRegistrationChartWidget;
use App\Models\User;
use App\UserTypes;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

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
 *
 * @package App\Filament\Resources
 */
final class UserResource extends Resource
{
    /**
     * At the core of our system sits this connection to the User model.
     *
     * Every time you create a new account, update someone's email, or make any other user-related changes, this line of code makes it happen.
     * Think of it as the bridge between what you see on screen and where the data lives in our database.
     *
     * @var string|null
     */
    protected static ?string $model = User::class;

    /**
     * Throughout our interface, when we talk about multiple users, we say "gebruikers".
     * You'll see this word in headers, navigation menus, and messages.
     * For example, when you're looking at the user list, you might see "25 gebruikers gevonden" or "Gebruikers beheren".
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'gebruikers';

    /**
     * When referring to just one user, we use "gebruiker".
     * This appears in messages like "Gebruiker toevoegen" or "Gebruiker bijwerken". Keeping everything in
     * Dutch helps our administrators feel at home in the interface.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'gebruiker';

    /**
     * In the navigation menu, we use a simple users icon to mark this section.
     * We chose this particular icon because it's universally recognized and immediately tells administrators they're in the user management area.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-users';

    /**
     * Organization matters, so we've grouped all user-related tools together under the UserManagement cluster.
     * This keeps things tidy and makes sure administrators can find everything they need in one place.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = UserManagement::class;

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
     * @param  Form $form   The Filament form builder instance
     * @return Form         The configured form ready for display
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Nieuwe gebruiker aanmaken')
                    ->icon('heroicon-o-user-plus')
                    ->iconColor('primary')
                    ->description('Vul hier alle benodigde informatie in voor het aanmaken van een nieuwe gebruiker op het Vlaams woordenboek')
                    ->compact()
                    ->columns(12)
                    ->schema([
                        Select::make('user_type')
                            ->label('Gebruikersgroep')
                            ->required()
                            ->native(false)
                            ->options(UserTypes::class)
                            ->columnSpan(3),
                        TextInput::make('firstname')
                            ->label('Voornaam')
                            ->required()
                            ->columnSpan(4),
                        TextInput::make('lastname')
                            ->label('Achternaam')
                            ->required()
                            ->columnSpan(5),
                        TextInput::make('email')
                            ->label('E-mail adres')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->columnSpan(12)
                    ])
            ]);
    }

    public static function getWidgets(): array
    {
        /** @phpstan-ignore-next-line */
        return [
            UserRegistrationChartWidget::class,
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SuggestionsRelationManager::class,
            RelationManagers\ReportsRelationManager::class,
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('tabs')
                    ->columnSpan(12)
                    ->tabs([
                        Tab::make('Algemene informatie')
                            ->columns(12)
                            ->icon('heroicon-o-identification')
                            ->schema(InfolistSchema::renderGeneralInformation()),
                        Tab::make('Deactiverings informatie')
                            ->columns(12)
                            ->visible(fn (User $user): bool => $user->isBanned())
                            ->icon('heroicon-o-lock-closed')
                            ->schema(InfolistSchema::renderDeactivationInformation())
                    ])
            ]);
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
     * @param  Table $table  The Filament table builder instance
     * @return Table         The fully configured table ready for display
     */
    public static function table(Table $table): Table
    {
        return $table
            ->heading('Gebruikersbeheer')
            ->description('In dit overzicht zie je alle geregistreerde gebruikers van het systeem. Je kunt hier gebruikersgegevens bekijken, accounts bewerken, rollen toewijzen of gebruikers verwijderen. Gebruik de zoek- en filteropties om snel de juiste gebruiker te vinden.')
            ->headerActions([
                Action::make('documentation-reference')
                    ->color('gray')
                    ->icon('heroicon-o-book-open')
                    ->label('Help'),
                CreateAction::make()
                    ->label('Gebruiker toevoegen')
                    ->icon('heroicon-o-user-plus')
            ])
            ->recordUrl(fn (User $user): string => self::getUrl('view', ['record' => $user]))
            ->columns([
                TextColumn::make('name')
                    ->iconColor('danger')
                    ->icon(fn (User $user): ?string  => $user->isBanned() ? 'tabler-shield-lock' : null)
                    ->label('Naam')
                    ->weight(FontWeight::Bold)
                    ->color(fn (User $user): string => $user->isBanned() ? 'danger' : 'primary')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user_type')
                    ->label('Gebruikers rol')
                    ->badge(),
                TextColumn::make('email')
                    ->label('E-mail adres')
                    ->searchable()
                    ->url(fn (User $user): string => 'mailto:' . $user->email),
                TextColumn::make('last_seen_at')
                    ->placeholder('-')
                    ->sortable()
                    ->since()
                    ->label('Laatste aanmelding'),
                TextColumn::make('created_at')
                    ->sortable()
                    ->label('Registratie tijdstip')
            ])
            ->filters([
                SelectFilter::make('user_type')
                    ->label('Gebruikersgroep')
                    ->native(false)
                    ->options(UserTypes::class),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    // Custom actions for activating/deactivating user accounts in the application platform.
                    Actions\BanAction::make()->visible(fn (User $user): bool => Gate::allows('deactivate', $user)),
                    Actions\UnbanAction::make()->authorize(fn (User $user): bool => Gate::allows('reactivate', $user)),

                    // Default delete actions
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
     * @return array<string, \Filament\Resources\Pages\PageRegistration> The route definitions for user management
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
