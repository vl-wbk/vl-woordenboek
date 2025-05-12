<?php

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use OwenIt\Auditing\Models\Audit;

class AuditsRelationManager extends RelationManager
{
    protected static string $relationship = 'audits';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Artikel wijzigingen')
            ->description('Een overzicht van de recente artikel wijzigingen. De actie om de wijziging te bekijken zal je meenemen naar de voorkant van de applicatie. Dit is wegens dat we meer vrijheid wouden in het weergeven van de informatie.')
            ->recordTitleAttribute('id')
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
