<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions;

use App\Enums\DataOrigin;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Enums\MaxWidth;
use LogicException;

/**
 * RemoveEditorAction is used to detach the editor from an article.
 *
 * This action allows a user to remove an assigned editor from an article.
 * When triggered, it shows a confirmation modal that explains what will happen, with custom text that
 * changes depending on whether the user is removing their own editor role or someone else's.
 * If confirmed, the action will execute a state transition, setting the article back to a
 * suggestion state. The action also handles notifying the user if the removal is successful
 * or if it fails.
 *
 * @property \App\Models\Article $record The dictionary article being submitted for publication
 *
 * @package App\Filament\Resources\ArticleResource\Actions
 */
final class RemoveEditorAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Icon used for navigation and in the confirmation modal.
     * To maintain consistency in the UI.
     *
     * @var string
     */
    protected static string $navigationIcon = 'tabler-link-minus';

    /**
     * Returns the default name for this action.
     * This name is used by the Filament system to identify the action within the navigation and user interface.
     *
     * @return string The default action name.
     */
    public static function getDefaultName(): string
    {
        return 'remove-editor';
    }

    /**
     * Sets up the configuration for the RemoveEditorAction.
     *
     * In the setup process the action is given a label ("Loskoppelen"), an icon, and a color ("danger") to indicate the seriousness of detaching the editor.
     * It also verifies that the user is authorized to perform this action by calling the 'detachEditor' policy on the record.
     * The action requires confirmation, displays a modal with a large width and no close button, and customizes the modal heading and description
     * based on whether the authenticated user is removing themselves or another editor. Custom notification titles for success and failure are also provided.
     *
     * Finally, when executed, the action triggers a state transition on the article to set it to a suggestion state.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Loskoppelen');
        $this->icon(self::$navigationIcon);
        $this->color('danger');
        $this->authorize('detachEditor', $this->record);


        $this->requiresConfirmation();
        $this->modalWidth(MaxWidth::Large);
        $this->modalCloseButton(false);
        $this->modalIcon(self::$navigationIcon);
        $this->modalHeading($this->getCustomUserBasedModalHeading());
        $this->modalDescription($this->getCustomUserBasedModalDescription());
        $this->modalSubmitActionLabel('Ja, ik ben zeker');

        $this->successNotificationTitle('De redacteur is losgekoppeld van het artikel');
        $this->failureNotificationTitle('We konden de redacteur niet loskoppelen van het artikel');

        $this->action(function (): void {
            if ($this->process(fn (): bool => $this->transitionBackBasedOnOrigin($this->record))) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }

    private function transitionBackBasedOnOrigin(Article $article): bool
    {
        return match ($article->origin) {
            DataOrigin::External => $article->articleStatus()->transitionToExternalData(),
            /** @phpstan-ignore-next-line */
            DataOrigin::Suggestion => $this->record->articleStatus()->transitionToSuggestion(),
            default => throw new LogicException('Could not found the correct origin to transtion'),
        };
    }

    /**
     * Returns a modal heading that adapts based on whether the authenticated user is removing themselves.
     *
     * When the user is detaching themselves from the editor role, a heading indicating the termination
     * of their editorial role is provided. Otherwise, a general heading for detaching the editor is returned.
     *
     * @return string The modal heading text.
     */
    private function getCustomUserBasedModalHeading(): string
    {
        $heading = ($this->isAuthenticatedUser())
            ? 'Redacteurschap beëindigen'
            : 'Redacteur loskoppelen';

        return trans($heading);
    }

    /**
     * Returns a modal description that explains the consequences of detaching an editor.
     *
     * The description differs slightly depending on whether the authenticated user is removing themselves or another editor.
     * When detaching oneself, it warns of loss of access and reversion of the article's status to suggestion, even if edits have already been made.
     * For removing another editor, it explains that the detachment is irreversible and the article will be set as a suggestion.
     *
     * @return string The modal description text.
     */
    private function getCustomUserBasedModalDescription(): string
    {
        $description = ($this->isAuthenticatedUser())
            ? 'Weet je zeker dat je jezelf wilt loskoppelen van dit artikel? Je verliest toegang en het artikel wordt opnieuw gemarkeerd als suggestie, ook als je al bewerkingen hebt gedaan. Deze actie kan niet ongedaan worden.'
            : 'Weet je zeker dat je deze redacteur wilt loskoppelen? Dit kan niet ongedaan worden gemaakt. Het artikel wordt teruggezet als suggestie en de redacteur verliest toegang.';

        return trans($description);
    }

    /**
     * Checks if the authenticated user is the same as the editor assigned to the article.
     *
     * This helper method determines whether the current user attempting the action is removing themselves instead of another editor.
     * It is used by the modal configuration methods to influence the displayed text accordingly.
     *
     * @return bool True if the authenticated user is the assigned editor, false otherwise.
     */
    private function isAuthenticatedUser(): bool
    {
        return $this->record->editor()->is(auth()->user());
    }
}
