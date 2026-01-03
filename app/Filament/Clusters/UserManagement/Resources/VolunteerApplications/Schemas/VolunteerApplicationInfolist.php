<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Schemas;

use App\Filament\Resources\Users\Schema\UserInfolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @todo Document this infolist
 */
final readonly class VolunteerApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()
                ->columnSpanFull()
                ->schema(components: [
                    self::userInformationTab(),
                    self::volunteerApplicationInformation(),           
                ])
        ]);
    }

    private static function userInformationTab(): Tab 
    {
        return Tab::make(label: __('filament/resources/volunteer-applications.infolist.user-information.heading'))
            ->columnSpanFull()
            ->icon(Heroicon::OutlinedUserCircle)
            ->columns(12)
            ->schema([
            TextEntry::make('user.name')
                ->label(label: __('user-resource.tables.columns.name'))
                ->icon('heroicon-o-user-circle')
                ->iconColor('primary')
                ->placeholder('-')
                ->columnSpan(3),

            TextEntry::make('user.firstname')
                ->label('Voornaam')
                ->columnSpan(3)
                ->placeholder('- niet opgegeven'),

            TextEntry::make('user.lastname')
                ->label('Achternaam')
                ->columnSpan(3)
                ->placeholder('- niet opgegeven'),

            TextEntry::make('user.email')
                ->label(label: __('user-resource.tables.columns.email'))
                ->badge()
                ->columnSpan(3),

            TextEntry::make('user.last_seen_at')
                ->label(label: __('user-resource.tables.columns.last-seen-at'))
                ->since()
                ->label('laatste aanmelding')
                ->dateTimeTooltip()
                ->icon('heroicon-o-clock')
                ->iconColor('primary')
                ->placeholder('-')
                ->columnSpan(3),

            TextEntry::make('user.user_type')
                ->label(label: __('user-resource.tables.columns.user-type'))
                ->badge()
                ->columnSpan(3)
                ->placeholder('- Geen gebruikersrollen toegewezen'),
        ]);
    }

    private static function volunteerApplicationInformation(): Tab 
    {
        return Tab::make(label: __('filament/resources/volunteer-applications.infolist.registration-info.heading'))
            ->columnSpanFull()
            ->icon(Heroicon::OutlinedDocumentText)
            ->columns(12)
            ->schema([
                Fieldset::make(label: __('filament/resources/volunteer-applications.infolist.registration-info.status-heading'))
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema([
                        TextEntry::make('user.name')
                            ->icon(Heroicon::OutlinedUserCircle)
                            ->iconColor('primary')
                            ->columnSpan(3),
                        TextEntry::make('role')
                            ->label(label: __('filament/resources/volunteer-applications.table.columns.position'))
                            ->badge()
                            ->columnSpan(3),
                        TextEntry::make('state')
                            ->badge()
                            ->columnSpan(3),
                        TextEntry::make('created_at')
                            ->label(label: __('filament/resources/volunteer-applications.table.columns.closed-at'))
                            ->date()
                            ->sinceTooltip()
                            ->columnSpan(3)
                    ]),

                TextEntry::make('motivation')
                    ->label(label: __('filament/resources/volunteer-applications.infolist.registration-info.motivation'))    
                    ->columnSpanFull(),

                TextEntry::make('background')
                    ->label(label: __('filament/resources/volunteer-applications.infolist.registration-info.background'))
                    ->columnSpanFull()
            ]);
    }
}
