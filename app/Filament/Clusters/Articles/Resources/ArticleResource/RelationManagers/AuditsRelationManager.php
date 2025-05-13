<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers;

use App\Filament\Resources\ArticleResource\Pages\ViewWord;
use App\UserTypes;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Models\Audit;

final class AuditsRelationManager extends RelationManager
{
    protected static string $relationship = 'audits';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->audits->count() > 0
            && new $pageClass() instanceof ViewWord
            && auth()->user()->user_type->isNot(UserTypes::Normal);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Artikel wijzigingen')
            ->description('Een overzicht van de recente artikel wijzigingen. De actie om de wijziging te bekijken zal je meenemen naar de voorkant van de applicatie. Dit is wegens dat we meer vrijheid wouden in het weergeven van de informatie.')
            ->recordTitleAttribute('id')
            ->emptyStateIcon('heroicon-o-eye')
            ->emptyStateHeading('Er zijn geen handelingen gevonden')
            ->emptyStateDescription('Het lijkt erop dat er momenteel nog geen handelingen zijn uitgevoerd voor dit artikel. Kom^later nog eens terug.')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->weight(FontWeight::SemiBold)
                    ->color('primary'),
                Tables\Columns\TextColumn::make('event')
                    ->label('Handeling')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Uitgevoerd door'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uitgevoerd op')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Audit $audit): string => route('change:information', $audit->getRouteKey()), shouldOpenInNewTab: true),
            ]);
    }
}
