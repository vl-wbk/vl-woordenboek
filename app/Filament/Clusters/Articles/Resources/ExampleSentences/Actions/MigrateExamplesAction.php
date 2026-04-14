<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ExampleSentences\Actions;

use App\Filament\Resources\Articles\RelationManagers\CommunityExamplesRelationManager;
use App\Filament\Resources\Articles\Schema\ArticleForm;
use App\Models\UserExample;
use App\States\ExampleSentence\Approved;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Fieldset;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

final class MigrateExamplesAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'migrate-usage-examples';
    }

    protected function setup(): void
    {
        parent::setUp();

        $this->configureButtons();
        $this->configureModal();

        $this->schema(schema: $this->configureSchema());

        // Action logic
        $this->action(function (array $data, $livewire): void {
            $article = $livewire->getOwnerRecord();

            DB::transaction(function () use ($data, $article) {
                // 1. Create the new examples
                collect($data['userExamples'])->each(fn (array $example) =>
                    UserExample::query()->create(array_merge($example, [
                        'article_id' => $article->id,
                        'user_id' => auth()->id(),
                        'status' => Approved::class,
                    ]))
                );

                // 2. Mark the article as migrated so the button disappears
                $config = $article->migration_configuration ?? [];
                $config['examples'] = true;

                $article->update(['migration_configuration' => $config,]);
            });

            Notification::make()
                ->title('Succesvol gemigreerd')
                ->success()
                ->send();
        });

        $this->successRedirectUrl(fn () => request()->header('Referer'));
    }

    private function configureButtons(): void
    {
        $this->label('Voorbeeldzinnen migreren');
        $this->icon(Heroicon::OutlinedArrowsRightLeft);
        $this->outlined();
        $this->visible(fn (CommunityExamplesRelationManager $livewire): bool => ! $livewire->getOwnerRecord()->migration_configuration['examples']);
    }

    private function configureModal(): void
    {
        $this->slideOver();

        $this->modalHeading('Voorbeeldzinnen migreren');
        $this->modalWidth(Width::SevenExtraLarge);
        $this->modalHeading('Voorbeeldzinnen migreren');
        $this->modalDescription('We schakelen in alle rust over naar nieuwe standaard voor voorbeeldzinnen met deze actie kunnen we het artikel in alle rust migreren.');
        $this->modalIcon(Heroicon::OutlinedArrowsRightLeft);
        $this->modalIconColor('primary');
    }

    private function configureSchema(): array
    {
         return [
            Callout::make('Opgepast')
                ->icon(Heroicon::OutlinedExclamationTriangle)
                ->description('Als u de wijzigingen opslaat wees dan zeker dat je alle voorbeelden van het oude formaat in de nieuwe standaard hebt overgezet na het opslaan is het niet mogelijk om de oude voorbeelden te raadplegen of te migreren.')
                ->danger(),

            Fieldset::make('Voorbeeldzinnen (oud formaat)')
                ->schema([
                    TextEntry::make('example')
                        ->hiddenLabel()
                        ->state(function (CommunityExamplesRelationManager $livewire): ?string {
                            return $livewire->getOwnerRecord()->example;
                        }),
                ]),

            Repeater::make('userExamples')
                ->label('Voorbeeldzinnen (nieuw formaat)')
                ->autofocus()
                ->compact()
                ->table([
                    Repeater\TableColumn::make('Voorbeeldzin'),
                    Repeater\TableColumn::make('Bron'),
                ])
                ->schema([
                    Textarea::make('example')
                        ->rows(1)
                        ->required(),

                     TextInput::make('source')
                        ->required(),
                ]),
         ];
    }
}
