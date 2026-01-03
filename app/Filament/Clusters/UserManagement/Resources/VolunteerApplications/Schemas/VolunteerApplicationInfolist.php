<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Schemas;

use App\Filament\Resources\Users\Schema\UserInfolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
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
            Section::make(heading: __('filament/resources/volunteer-applications.infolist.user-information.heading'))
                ->description(description: __('filament/resources/volunteer-applications.infolist.user-information.description'))
                ->collapsible()
                ->compact()
                ->columns(12)
                ->columnSpanFull()
                ->icon(icon: Heroicon::OutlinedUserCircle)
                ->iconColor(color: 'primary')
                ->schema(self::userInformationEntryComponents()),

            Tabs::make()
                ->columnSpanFull()
                ->schema(components: [
                    self::statusInformationTab(), 
                    self::backgroundInformationTab(), 
                    self::motivationInformationTab(),           
                ])
        ]);
    }

    private static function statusInformationTab(): Tab 
    {
        return Tab::make(label: __('filament/resources/volunteer-applications.tabs.status.heading'))
            ->icon(icon: Heroicon::OutlinedInformationCircle)
            ->columns(12)
            ->columnSpanFull()
            ->schema([
                TextEntry::make('role')
                    ->label(label: __('filament/resources/volunteer-applications.table.columns.position'))
                    ->badge()
                    ->columnSpan(3),
                TextEntry::make('state')
                    ->badge()
                    ->columnSpan(3),
                TextEntry::make('created_at')
                    ->label(label: __('filament/resources/volunteer-applications.table.columns.created-at'))
                    ->date()
                    ->sinceTooltip()
                    ->columnSpan(3),
                TextEntry::make('closed_at')
                    ->label(label: __('filament/resources/volunteer-applications.table.columns.closed-at'))
                    ->date()
                    ->sinceTooltip()
                    ->placeholder('-')
                    ->columnSpan(3),
            ]);
    }

    private static function userInformationEntryComponents(): array 
    {
        return [
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

            TextEntry::make('user.roles.name')
                ->label('Huidige permissie(s)')
                ->columnSpan(6)
                ->icon(Heroicon::OutlinedKey)
                ->color('danger')
                ->badge()
        ];
    }

    private static function backgroundInformationTab(): Tab 
    {
        return Tab::make(label: __('filament/resources/volunteer-applications.tabs.headings.background'))
            ->columnSpanFull()
            ->icon(Heroicon::OutlinedClipboardDocumentList)
            ->columns(12)
            ->schema([
                TextEntry::make('background')
                    ->hiddenLabel()
                    ->columnSpanFull()
            ]);
    }

    private static function motivationInformationTab(): Tab 
    {
        return Tab::make(label: __('filament/resources/volunteer-applications.tabs.headings.motivation'))
            ->columnSpanFull()
            ->icon(Heroicon::OutlinedChatBubbleBottomCenter)
            ->columns(12)
            ->schema([
                TextEntry::make('motivation')
                    ->hiddenLabel()
                    ->columnSpanFull()
            ]);
    }
}
