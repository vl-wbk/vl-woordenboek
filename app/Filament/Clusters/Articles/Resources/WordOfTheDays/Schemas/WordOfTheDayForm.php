<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

final readonly class WordOfTheDayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Planner')
                    ->columns(12)
                    ->icon(Heroicon::OutlinedClock)
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->compact()
                    ->description('Koppel een woord aan een specifieke dag en gebeurtenis.')
                    ->schema([
                        DatePicker::make('scheduled_for')
                            ->label('Ingeplande datum')
                            ->required()
                            ->columnSpan(4)
                            ->unique(ignoreRecord: true)// This checks the DB before submitting
                            ->native(false),

                        Select::make('article_id')
                            ->label('Selecteer Woord')
                            ->relationship(
                                name: 'article', 
                                titleAttribute: 'word', 
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->where('disclaimer_id', null)
                                    ->whereNotNull('published_at')
                            )
                            ->searchable()
                            ->columnSpan(8)
                            ->preload()
                            ->required(),

                        Textarea::make('scheduling_reason')
                            ->required()
                            ->columnSpanFull()
                            ->label('Gebeurtenis / Aanleiding')
                            ->placeholder('Bijv: Internationale Vrouwendag of Start van de Lente')
                            ->rows(5)
                            ->helperText('Waarom is dit het woord van de dag?'),
                    ])  
            ]);
    }
}
