<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;

final readonly class WordOfTheDaysInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components(components: [
                TextEntry::make('planner.name')
                    ->label('Ingepland door')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->columnSpan(3)
                    ->iconColor('primary'),

                TextEntry::make('article.word')
                    ->label('Artikel')
                    ->weight(FontWeight::Bold)
                    ->columnSpan(3)
                    ->color('primary'),

                TextEntry::make('scheduled_for')
                    ->label('Ingepland voor')
                    ->date()
                    ->columnSpan(3)
                    ->sinceTooltip(),

                TextEntry::make('created_at')
                    ->label('Ingepland op')
                    ->date()
                    ->columnSpan(3)
                    ->sinceTooltip(),

                TextEntry::make('scheduling_reason')
                    ->columnSpan(12)
                    ->label('Gebeurtenis / Aanleiding'),
            ]);
    }
}