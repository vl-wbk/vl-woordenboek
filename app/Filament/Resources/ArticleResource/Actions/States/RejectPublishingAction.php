<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions\States;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;

/**
 * RejectPublishingAction handles the rejection of articles submitted for publication.
 *
 * This action manages the transition of articles from the review state back to the editing state.
 * It implement authorization checks to ensure only authorized editors can reject publication requests.
 * The action uses clear visual indicators through red coloring and X-mark iconography to signify its negative nature.
 *
 * @property \App\Models\Article $record The articles being rejected for publication.
 *
 * @package App\Filament\Resources\ArticleResource\Actions\States
 */
final class RejectPublishingAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Defines the visual icon for the reject action.
     * Uses the X-mark icon from Heroicons to maintain consistency with the application's visual language whulde clearly indicating a negative action.
     */
    protected string $actionIcon = 'heroicon-o-x-mark';

    /**
     * Provides the default name for the action in Dutch, maintaining consistency with the application's primary language interface.
     * This text appears in rejection buttons throughout the editorial interface.
     */
    public static function getDefaultName(): string
    {
        return trans('Publicatie afwijzen');
    }

    /**
     * Configures the action's behavior and visual presentation.
     *
     * This setup method establishes the action's appearance and handling.
     * It uses a danger color scheme and X-mark icon to indicate rejection.
     * The method implements authorization checks and provides a clear confirmation dialog to prevent accidental rejections. Upon confirmation, it transitions the article back to editing state.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->color('danger');
        $this->icon($this->actionIcon);
        $this->authorize('reject-publication', $this->record);

        // Confirmation config
        $this->requiresConfirmation();
        $this->modalWidth(MaxWidth::ThreeExtraLarge);
        $this->modalIcon($this->actionIcon);
        $this->modalHeading('Voorstel tot publicatie afwijzen');
        $this->modalDescription($this->getModalDescription());
        $this->form($this->getModalForm());
        $this->modalSubmitActionLabel('Ja, ik weet het zeker');

        $this->successNotificationTitle('We hebben het artikel succesvol teruggestuurd naar de redactie.');
        $this->failureNotificationTitle('Helaas! Er is iets misgelopen');

        $this->action(function (array $data): void {
            if ($this->process(fn () => $this->record->articleStatus()->transitionToEditing($data['reason']))) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }

    public function getModalDescription(): string
    {
        return '
            Indien het voorstel tot publicatie afwijst zal het artikel terug geplaatst worden in de lijst van te bewerken artikelen.
            Echter vragen we je wel om reden tot afkeuring te geven. Zodat de redacteur in kwestie terug aan de slag kan gaan op basis van de notitie.
        ';
    }

    /**
     * @return array<int, Textarea>
     */
    private function getModalForm(): array
    {
        return [
            Textarea::make('reason')
                ->label('Reden tot afkeuring')
                ->required()
                ->rows(4)
                ->placeholder('Korte motivering van de afkeuring.'),
        ];
    }
}
