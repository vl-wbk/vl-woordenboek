<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions\States;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * Class UnarchiveAction
 *
 * This class defines a Filament Action for unarchiving an article.
 * It allows authorized users to restore an archived article, making it available again in the Vlaams Woordenboek.
 *
 * The action performs the following steps:
 *  1. Authorizes the user to perform the 'unarchive' action on the given article.
 *  2. Presents a confirmation modal to the user to prevent accidental unarchiving.
 *  3. If confirmed, transitions the article's state to 'Released', making it public.
 *  4. Displays a success or failure notification to the user.
 *
 * @property \App\Models\Article $record The database entoty from the given dictionary article.
 *
 * @package App\Filament\Resources\ArticleResource\Actions\States
 */
final class UnarchiveAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Returns the default name for the action.
     *
     * This method provides a human-readable name for the action, which is
     * displayed in the user interface. The name is retrieved from the
     * translation file using the `trans()` helper function.
     *
     * @return string The translated name of the action ("Herstellen").
     */
    public static function getDefaultName(): string
    {
        return trans('Herstellen');
    }

    /**
     * Configures the action.
     *
     * This method sets up the action's properties, such as its authorization
     * requirements, color, icon, confirmation modal, and success/failure
     * notifications. It also defines the action's logic, which transitions
     * the article to the "released" state.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Authorize the action based on the 'unarchive' ability and the current record.
        $this->authorize('unarchive', $this->record);

        // Customize the action's appearance.
        $this->color('gray');
        $this->icon('heroicon-o-archive-box-x-mark');

        // Require user confirmation before proceeding.
        $this->requiresConfirmation();

        // Configure the confirmation modal.
        $this->modalHeading('Artikel herstellen');
        $this->modalIcon('heroicon-o-archive-box-x-mark');
        $this->modalIconColor('warning');
        $this->modalDescription('Indien u het artikel terug haalt uit het archief haalt. Zal het terug gepubliceerd worden in het Vlaams Woordenboek. Bent u zeker dat u dit wilt doen?');
        $this->modalSubmitActionLabel('Ja, ik weet het zeker');

        // Set up notifications for success and failure.
        $this->successNotificationTitle('het artikel is terug uit het archief gehaald');
        $this->failureNotificationTitle('Helaas! Er is iets misgelopen!');

        // Define the action's execution logic.
        $this->action(function (): void {
            // Attempt to transition the article to the "released" state within a process that can be customized.
            if ($this->process(fn () => $this->record->articleStatus()->transitionToReleased())) {
                // If successful, display a success message.
                $this->success();
                return;
            }

            // If the transition fails, display a failure message.
            $this->failure();
        });
    }
}
