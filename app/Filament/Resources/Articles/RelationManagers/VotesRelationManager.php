<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\RelationManagers;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Articles\Pages\ViewWord;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Override;
use Overtrue\LaravelVote\Vote;

final class VotesRelationManager extends RelationManager
{
    protected static ?string $resource = ArticleResource::class;

    protected static string $relationship = 'votes';

    protected static ?string $title = 'Feedback (kwaliteitsscore)';

    #[Override]
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return Cache::flexible('votes-feedback-count:' . $ownerRecord->getRouteKey(), [150, 300], function () use ($ownerRecord): int|string {
            return (string) $ownerRecord->downvotes()->whereNotNull('feedback')->count() ?? null;
        });
    }

    #[Override]
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->votes()->whereNotNull('feedback')->exists()
            && new $pageClass() instanceof ViewWord;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereNotNull('feedback'))
            ->heading(self::$title)
            ->description('Overzicht van alle feedback bij negatieve beoordelingen. Gebruik deze inzichten om het artikel waar nodig te verbeteren.')
            ->columns(components: $this->registerTableColumns())
            ->recordActions(actions: $this->registerRecordActions());
    }

    private function registerTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label('#')
                ->color('primary')
                ->weight(FontWeight::ExtraBold)
                ->sortable()
                ->searchable(),

            TextColumn::make('user.name')
                ->label('Gemeld door')
                ->placeholder('Verwijderde gebruiker')
                ->icon(Heroicon::OutlinedUserCircle)
                ->iconColor('primary')
                ->sortable(),

            TextColumn::make('feedback')
                ->label('Ingezonden feedback')
                ->searchable()
                ->limit(200),

            TextColumn::make('created_at')
                ->label('Gemeld op')
                ->date(format: 'd F Y')
                ->sortable(),
        ];
    }


    private function registerRecordActions(): array
    {
        return [
            ViewAction::make()
                ->label('Feedback bekijken')
                ->modalHeading('Feedback informatie')
                ->modalIcon(Heroicon::OutlinedChatBubbleLeftEllipsis)
                ->modalDescription(fn (Vote $vote): string => $this->translatedModalDescription($vote))
                ->schema([
                    TextEntry::make('feedback')
                        ->columnSpanFull()
                        ->hiddenLabel(),
                ]),
        ];
    }

    private function translatedModalDescription(Vote $vote): string
    {
        return __('Ingezonden door :user op :time', [
            'user' => $vote->user->name ?? 'Verwijderde gebruiker',
            'time' => $vote->created_at->translatedFormat('d F Y')
        ]);
    }
}
