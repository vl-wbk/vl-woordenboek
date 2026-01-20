<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Actions\States;

use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

/**
 * RejectPublishingAction handles the rejection of articles submitted for publication.
 *
 * This action manages the transition of articles from the review state back to the editing state.
 * It implement authorization checks to ensure only authorized editors can reject publication requests.
 * The action uses clear visual indicators through red coloring and X-mark iconography to signify its negative nature.
 *
 * @package App\Filament\Resources\Articles\Actions\States
 */
final class RejectPublishingAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Defines the visual icon for the reject action.
     * Uses the X-mark icon from Heroicons to maintain consistency with the application's visual language would clearly indicating a negative action.
     */
    protected string $actionIcon = 'heroicon-o-x-mark';

    /**
     * Provides the default name for the action in Dutch, maintaining consistency with the application's primary language interface.
     * This text appears in rejection buttons throughout the editorial interface.
     */
    public static function getDefaultName(): string
    {
        return trans('Afwijzen');
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

        $this->authorize('publish');
        $this->hidden(fn (Article $article): bool => $article->trashed());

        // Confirmation config
        $this->requiresConfirmation();
        $this->modalWidth(Width::ThreeExtraLarge);
        $this->modalIcon($this->actionIcon);
        $this->modalHeading('Voorstel tot publicatie afwijzen');
        $this->modalDescription($this->getModalDescription());
        $this->schema($this->getModalForm());
        $this->modalSubmitActionLabel('Ja, ik weet het zeker');

        $this->successNotificationTitle('We hebben het artikel succesvol teruggestuurd naar de redactie.');
        $this->failureNotificationTitle('Helaas! Er is iets misgelopen');

        $this->action(function (array $data): void {
            if ($this->process(fn (Article $article) => $this->handleArticleRejection($article, $data['reason']))) {
                $this->success();

                return;
            }

            $this->failure();
        });
    }

    /**
     * Handles the core logic for rejecting the article.
     * This method attempts to transition the article's status back to 'editing' and sends a detailed notification to the article's assigned editor if the transition is successful.
     *
     * @param  Article  $article The article model instance to be rejected.
     * @param  string   $reason  The mandatory reason provided by the reviewer for the rejection.
     * @return bool              True if the status transition was successful and the editor exists, false otherwise.
     */
    public function handleArticleRejection(Article $article, string $reason): bool
    {
        $transition = $article->articleStatus()->transitionToEditing($reason);

        if ($transition && $article->editor()->exists()) {
            $article->editor->notify(
                Notification::make()
                    ->title('Publicatieverzoek afgewezen')
                    ->danger()
                    ->icon(Heroicon::XCircle)
                    ->body('Een eindredacteur heeft het publicatieverzoek voor een artikel afgewezen. In de notities kan je de beweegredenen raadplegen.')
                    ->actions([
                        Action::make('view-article')
                            ->label('Bekijk artikel')
                            ->url(ArticleResource::getUrl('view', ['record' => $article]))
                            ->markAsRead()
                    ])
                    ->toDatabase()
            );
        }

        return $transition;
    }

    /**
     * Provides the descriptive text for the confirmation modal.
     * This text informs the user that the article will return to the editing queue and emphasizes the need for a rejection reason so the editor can proceed.
     *
     * @return string The descriptive text (in Dutch).
     */
    public function getModalDescription(): string
    {
        return '
            Indien het voorstel tot publicatie afwijst zal het artikel terug geplaatst worden in de lijst van te bewerken artikelen.
            Echter vragen we je wel om reden tot afkeuring te geven. Zodat de redacteur in kwestie terug aan de slag kan gaan op basis van de notitie.
        ';
    }

    /**
     * Defines the form components for the rejection confirmation modal.
     * The form consists of a single, required Textarea field for capturing the rejection reason.
     *
     * @return array<int, Textarea> An array containing the form schema components.
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
