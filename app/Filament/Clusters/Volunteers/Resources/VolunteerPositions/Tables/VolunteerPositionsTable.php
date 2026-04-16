<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final readonly class VolunteerPositionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Overzicht van alle vrijwilliger posities')
            ->description('In de onderstaande tabel kan je alle geregistreerde posities bekijken voor vrijwilligers in het Vlaams Woordenboek')
            ->columns(self::registerTableColumnComponents())
            ->recordActions(self::registerRecordActions());
    }

    /**
     * @return array<int, TextColumn|IconColumn>
     */
    private static function registerTableColumnComponents(): array
    {
        return [
            TextColumn::make('name')
                ->label('Positie')
                ->searchable()
                ->sortable()
                ->color('primary')
                ->weight(FontWeight::Bold),

            IconColumn::make('is_open')
                ->label('Aanmeldbaar')
                ->boolean(),

            TextColumn::make('tag_line')
                ->label('Tag line/sub titel')
                ->placeholder('- Niet opgegeven'),

            TextColumn::make('roles.name')
                ->label('geassoc. permissiegroep'),

            TextColumn::make('associated_user_group')
                ->label('geassoc. gebruikersgroep')
                ->badge(),

        ];
    }

    /**
     * @return array<ActionGroup|ViewAction>
     */
    private static function registerRecordActions(): array
    {
        return [
            ViewAction::make(),

            ActionGroup::make([
                EditAction::make(),
                DeleteAction::make()
                    ->modalHeading('Vrijwilligers positie verwijderen')
                    ->modalDescription('U staat op het punt om een vrijwilligerspositie te verwijderen. Bij het verwijderen zullen ook alle gegevens omtrent aanmeldingen verloren gaan. Weet je zeker dat je dit wilt doen?'),
            ]),
        ];
    }
}
