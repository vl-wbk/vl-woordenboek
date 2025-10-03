<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\Bans;

use App\Filament\Clusters\UserManagement\Resources\Bans\Concerns\TableSchemeLayout;
use App\Filament\Clusters\UserManagement\Resources\Bans\Concerns\TableActions;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Clusters\UserManagement\Resources\Bans\Pages\ListBans;
use Filament\Resources\Pages\PageRegistration;
use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Clusters\UserManagement\Resources\BanResource\Concerns;
use App\Filament\Clusters\UserManagement\Resources\BanResource\Pages;
use Cog\Laravel\Ban\Models\Ban;
use Filament\Resources\Resource;
use Filament\Tables\Table;

/**
 * Filament resource implementation for managing user account deactivations.
 *
 * Integrates with cybercog/ban for persistent ban storage and lifecycle management.
 * Implements a trait-based architecture for table configurations,  utilizing Filament's table builder API for the administrative interface.
 * Supports temporary and permanent bans with automated expiration handling through Laravel's task scheduling.
 *
 * @package App\Filament\Clusters\UserManagement\Resources
 */
final class BanResource extends Resource
{
    use TableSchemeLayout;
    use TableActions;

    /**
     * We use a dutch interface label throughout the admin panel.
     * This friendly term "Deactiveringen" appears in navigation menus and headers to maintain consistency with our Dutch-speaking community.
     */
    protected static ?string $pluralLabel = 'Deactiveringen';

    /**
     * Behind the scenes, we're utilizing the Ban model from the laravel-ban package.
     * This model handles all the complexities of ban management, including timestamps, expiration handling, and ban metadata.
     */
    protected static ?string $model = Ban::class;

    /**
     * For visual recognition, we use a shield-lock icon from the Tabler icon set.
     * This icon perfectly represents the security aspect of account deactivations while maintaining a clean, professional look.
     */
    protected static string | \BackedEnum | null $navigationIcon = 'tabler-shield-lock';

    /**
     * This resource belongs to the broader user management family.
     * By specifying the UserManagement cluster, we ensure this resource appears alongside other user-related features in the admin panel.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = UserManagementCluster::class;

    /**
     * @todo Document this function
     */
    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                TextEntry::make('bannable.name')
                    ->columnSpan(4)
                    ->label('Gebruiker')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary'),
                TextEntry::make('bannable.created_at')
                    ->columnSpan(4)
                    ->label('Gedeactiveerd op')
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary'),
                TextEntry::make('expired_at')
                    ->columnSpan(4)
                    ->label('Reactiverings datum')
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->default('tot nader order'),
                TextEntry::make('comment')
                    ->columnSpanFull()
                    ->label('Reden tot deactivering')
                    ->default('Geen reden tot deactivering opgegegeven'),
            ]);
    }

    /**
     * Here's where we set up the main interface for managing bans.
     * The table provides a clear overview of all deactivated accounts, with helpful context about automatic reactivation of expired bans.
     *
     * You'll notice we've separated concerns into traits for the table layout, filters, and actions.
     * This makes it easier to maintain and extend each aspect independently.
     *
     * @param  Table $table  Laravel Filament's table instance used for configuring the instance.
     * @return Table         The configured table instance
     */
    public static function table(Table $table): Table
    {
        return $table
            ->heading('Gedeactiveerde gebruikeraccounts')
            ->description('Overzicht van alle gedeactiveerde gebruiker accounts. Wanneer een deactivering verloopt zal deze automatisch terug geactiveerd worden in het systeem.')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen deactiveringen gevonden')
            ->emptyStateDescription('Het lijkt erop dat er momenteel geen gebruikers zijn gedactiveerd in het Vlaams Woordenboek')
            ->columns(self::getTableColumnLayout())
            ->recordActions(self::getTableActions());
    }

    /**
     * Currently, we're keeping things focused with just an index page for listing bans.
     * If you need to add more pages (like detailed views or custom forms), this is where you'd register them.
     * The routing is handled automatically by Filament's resource system.
     *
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return ['index' => ListBans::route('/')];
    }
}
