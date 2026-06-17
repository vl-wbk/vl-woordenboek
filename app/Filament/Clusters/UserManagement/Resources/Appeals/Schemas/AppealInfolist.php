<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\Appeals\Schemas;

use App\Models\Appeal;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;

final readonly class AppealInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            self::getAppealDetailsSection(),
            self::getModerationSection(),
        ]);
    }

    protected static function getAppealDetailsSection(): Section
    {
        return Section::make('Algemene gegevens van het beroep')
            ->description('Alle algemene gegevens van het beroep dat is ingediend door de gebruiker')
            ->icon(Heroicon::OutlinedDocumentText)
            ->iconSize(IconSize::Medium)
            ->iconColor('primary')
            ->columnSpanFull()
            ->compact()
            ->columns(12)
            ->schema([
                TextEntry::make('status')
                    ->label('Status')
                    ->columnSpan(4)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'  => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'  => 'In behandeling',
                        'approved' => 'Toegekend',
                        'rejected' => 'Afgewezen',
                        default    => $state,
                    }),

                TextEntry::make('user.name')
                    ->label('Gebruiker')
                    ->columnSpan(4)
                    ->icon(Heroicon::OutlinedUser),

                TextEntry::make('reputationLog.points')
                    ->label('Betwiste reputatie punten')
                    ->columnSpan(4)
                    ->icon('heroicon-o-arrow-trending-down')
                    ->badge()
                    ->color('danger'),

                TextEntry::make('created_at')
                    ->label('Ingediend')
                    ->columnSpan(4)
                    ->since()
                    ->icon('heroicon-o-clock'),

                TextEntry::make('reputationLog.reason')
                    ->label('Aangevochten aanpassing')
                    ->columnSpanFull(),

                TextEntry::make('reason')
                    ->label('Reden van beroep')
                    ->columnSpanFull(),
            ]);
    }

    protected static function getModerationSection(): Section
    {
        return Section::make('Beoordeling')
            ->description('De gegevens omtrent de beoordelingen van het beroep')
            ->icon(Heroicon::OutlinedClipboardDocument)
            ->iconColor('primary')
            ->iconSize(IconSize::Medium)
            ->columnSpanFull()
            ->compact()
            ->columns(12)
            ->schema([
                TextEntry::make('moderator.name')
                    ->label('Beoordeeld door')
                    ->icon('heroicon-o-user-circle')
                    ->columnSpan(6)
                    ->placeholder('Nog niet beoordeeld'),

                TextEntry::make('reviewed_at')
                    ->label('Beoordeeld op')
                    ->since()
                    ->columnSpan(6)
                    ->icon('heroicon-o-clock')
                    ->placeholder('—'),

                TextEntry::make('moderator_note')
                    ->label('Notitie')
                    ->columnSpanFull()
                    ->placeholder('Geen notitie'),
            ])
            ->columns(2)
            ->collapsed(fn (Appeal $record): bool => $record->status === 'pending');
    }
}
