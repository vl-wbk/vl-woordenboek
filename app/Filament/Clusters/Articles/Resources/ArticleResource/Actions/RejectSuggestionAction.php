<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Actions;

use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Override;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

final class RejectSuggestionAction extends Action
{
    use CanCustomizeProcess;

    protected Heroicon $actionIcon = Heroicon::OutlinedArchiveBoxXMark;

    public static function getDefaultName(): string
    {
        return 'reject-suggestion';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Suggestie afwijzen');
        $this->color('gray');
        $this->icon($this->actionIcon);

        $this->authorize('reject-suggestion', $this->record);

        $this->modal();
        $this->modalIcon($this->actionIcon);
        $this->modalIconColor('danger');
        $this->modalCloseButton(false);
        $this->modalWidth(Width::Large);
        $this->requiresConfirmation();
        $this->modalFooterActions(function (Action $action): array {
            return [
                $this->getModalCancelAction(),
                $this->getModalSubmitAction()
                    ->color('danger')
            ];
        });

        $this->schema(schema: $this->formSchema());

        $this->action(function (array $data): void {
            if ($this->process(fn (Article $article): bool => $article->articleStatus()->transitionToRejectedSuggestion($data))) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }

    protected function formSchema(): array
    {
        return [
            Textarea::make('rejection_reason')
                ->required()
                ->hiddenLabel()
                ->rows(5)
                ->placeholder('Beschrijf kort waarom de suggestie word afgewezen in het Vlaams Woordenboek.')
                ->showCharacterCounter(false),
        ];
    }

    public function getModalDescription(): string
    {
        return __('Wijs deze suggestie af en geef korte feedback zodat de gebruiker weet waarom deze niet wordt gepubliceerd.');
    }
}
