<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Actions\States;

use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs;
use Filament\Support\Enums\Alignment;
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
        return 'reject-dictionary-article';
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
        $this->label('Afwijzen');
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

        $this->successNotificationTitle('We hebben het artikel succesvol teruggestuurd naar de redacteur.');
        $this->failureNotificationTitle('Helaas! Er is iets misgelopen');

        $this->action(function (array $data): void {
            if ($this->process(fn (Article $article) => $this->handleArticleRejection($article, $data['feedback']))) {
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
     * @param  Article                 $article The article model instance to be rejected.
     * @param  array{reason: string}   $reason  The mandatory reason provided by the reviewer for the rejection.
     * @return bool                             True if the status transition was successful and the editor exists, false otherwise.
     */
    public function handleArticleRejection(Article $article, array $reason): bool
    {
        $transition = $article->articleStatus()->transitionToRejectedPublication($reason);

        if ($transition && $article->editor()->exists()) {
            $article->editor->notify(
                Notification::make()
                    ->title('Publicatieverzoek afgewezen')
                    ->danger()
                    ->icon(Heroicon::XCircle)
                    ->body('Een eindredacteur heeft het publicatieverzoek voor een artikel afgewezen. In het bewerkingsformulier kun je de feedback raadplegen.')
                    ->actions([
                        Action::make('view-article')
                            ->label('Wijzig artikel')
                            ->url(ArticleResource::getUrl('edit', ['record' => $article]))
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
        return 'U staat op het punt om een voorstel tot publicatie af te wijzen. Vergeet niet de nodige bijsturing te documenteren zodat de redacteur ermee aan de slag kan gaan.';
    }

    /**
     * Defines the form components for the rejection confirmation modal.
     * The form consists of a single, required Textarea field for capturing the rejection reason.
     *
     * @return array<int, Tabs> An array containing the form schema components.
     */
    private function getModalForm(): array
    {
        return [
            Tabs::make()
                ->schema([
                    Tabs\Tab::make('Algemene informatie')
                        ->icon(Heroicon::InformationCircle)
                        ->schema([
                            Textarea::make('feedback.general-information')
                                ->hiddenLabel()
                                ->rows(4)
                                ->placeholder('Beschrijf kort wat er mis is met de algemene informatie.')
                                ->helperText('Indien er geen opmerkingen zijn gelieve dit veld leeg te laten'),
                        ]),
                    Tabs\Tab::make('Regio en status')
                        ->icon(Heroicon::MapPin)
                        ->schema([
                            Textarea::make('feedback.region-status')
                                ->hiddenLabel()
                                ->rows(4)
                                ->placeholder('Beschrijf kort wat er mis is met de regio en status informatie.')
                                ->helperText('Indien er geen opmerkingen zijn gelieve dit veld leeg te laten'),
                        ]),
                    Tabs\Tab::make('Bron gegevens')
                        ->icon(Heroicon::BookOpen)
                        ->schema([
                            Textarea::make('feedback.sources')
                                ->hiddenLabel()
                                ->rows(4)
                                ->placeholder('Beschrijf kort wat er mis is met de bron gegevens')
                                ->helperText('Indien er geen opmerkingen zijn gelieve dit veld leeg te laten'),
                        ])
                ]),
        ];
    }
}
