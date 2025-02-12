<?php

declare(strict_types=1);

namespace App\Filament\Resources\SuggestionResource\Actions;

use App\Contracts\ChecksAuthorizationBasedOnStatus;
use App\Enums\SuggestionStatus;
use App\Models\Suggestion;
use App\Models\Word;
use App\UserTypes;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class AcceptSuggestionAction extends Action implements ChecksAuthorizationBasedOnStatus
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'Goedkeuren')
            ->icon(self::getActionIcon())
            ->modalHeading(fn(Suggestion $suggestion): string => trans(':woord goedkeuren als lemma.', ['woord' => $suggestion->word]))
            ->modalDescription(fn (Suggestion $suggestion): string => trans('De suggestie voor het woord :woord goedkeuren als lemma en opnemen in het woordenboek', ['woord' => $suggestion->word]))
            ->modalIcon(self::getActionIcon())
            ->modalIconColor('success')
            ->color('gray')
            ->modalWidth(MaxWidth::ScreenLarge)
            ->visible(fn (Suggestion $suggestion): bool => self::performActionBasedOnStatus($suggestion))
            ->fillForm(fn(Suggestion $suggestion): array => $suggestion->toArray())
            ->form(self::configureModalForm())
            ->action(fn (Suggestion $suggestion, array $data) => self::configureModalAction($suggestion, $data));
    }

    public static function performActionBasedOnStatus(Model $model): bool
    {
        if ($model->assignee()->exists() && $model->assignee()->isNot(auth()->user())) {
            return false;
        }

        return $model->status->notIn(enums: [SuggestionStatus::Accepted, SuggestionStatus::Rejected]);
    }

    private static function configureModalForm(): array
    {
        return [
            Grid::make(12)
                ->schema([
                    TextInput::make('word')
                        ->label('Woord')
                        ->required()
                        ->maxLength(255)
                        ->translateLabel()
                        ->columnSpan(4),
                    TextInput::make('characteristics')
                        ->label('Kenmerken')
                        ->required()
                        ->maxLength(255)
                        ->translateLabel()
                        ->columnSpan(8),
                    Select::make('regions')
                        ->label("Regio's")
                        ->multiple()
                        ->columnSpan(12)
                        ->preload()
                        ->minItems(1)
                        ->relationship('regions', 'name'),
                    Textarea::make('description')
                        ->label('Beschrijving')
                        ->translateLabel()
                        ->required()
                        ->columnSpan(12)
                        ->cols(4),
                    Textarea::make('example')
                        ->label('Voorbeeld')
                        ->translateLabel()
                        ->required()
                        ->columnSpan(12)
                        ->cols(4),
                ])
        ];
    }

    private static function configureModalAction(Suggestion $suggestion, array $data): mixed
    {
        return DB::transaction(function () use ($suggestion, $data): Word {
            $suggestion->update(attributes: ['status' => SuggestionStatus::Accepted, 'approver_id' => auth()->user()->id]);

            // Start repllication process to the word table (Lemma's)
            return tap(self::createNewLemma($data), function (Word $lemma) use ($suggestion): void {
                $suggestionRegions = $suggestion->fresh()->regions->pluck('id')->toArray();

                $lemma->syncRegions($suggestionRegions);
                $lemma->author()->associate(auth()->user())->save();
            });
        });
    }

    private static function createNewLemma(array $attributes): Word
    {
        return Word::query()->create($attributes);
    }

    private static function getActionIcon(): string
    {
        return SuggestionStatus::Accepted->getIcon();
    }
}
