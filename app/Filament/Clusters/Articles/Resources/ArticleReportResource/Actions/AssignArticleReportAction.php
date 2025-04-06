<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions;

use App\States\Reporting\Status;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Support\Facades\Gate;

/**
 * Represents the action for assigning an article report to the current user.
 *
 * The `AssignArticleReportAction` class defines the logic and configuration for handling the assignment of an article report to the currently authenticated user.
 * This action transitions the report's state to "In Progress" and ensures that only authorized users can perform the assignment.
 *
 * The action is integrated into the Filament admin panel and provides a user-friendly interface for managing article reports.
 * It includes visual indicators, such as an icon and label, and displays success or failure notifications based on the outcome of the assignment process.
 *
 * @package App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions
 */
final class AssignArticleReportAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Returns the default name for the action.
     *
     * The default name is used to identify the action within the Filament admin panel.
     * In this case, the name is set to "melding-behandelen" (Dutch for "handle report").
     *
     * @return string|null The default name of the action.
     */
    public static function getDefaultName(): ?string
    {
        return 'melding-behandelen';
    }

    /**
     * Configures the action's behavior and appearance.
     *
     * This method sets up the action's icon, color, label, visibility, and notifications.
     * It also defines the logic for transitioning the report's state to "In Progress."
     *
     * - The icon is dynamically retrieved from the "In Progress" state.
     * - The visibility is determined by the user's authorization to mark the report as "In Progress."
     * - Success and failure notifications are displayed based on the outcome of the action.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->icon(Status::InProgress->getIcon());
        $this->color('gray');
        $this->label('melding behandelen');
        $this->visible(fn (): bool => Gate::allows('markInProgress', $this->record));

        $this->successNotificationTitle('Je bent toegewezen aan de artikel melding');
        $this->failureNotificationTitle('Helaas konden we je niet toewijzen aan de melding');


        $this->action(function (): void {
            if ($this->process(fn (): bool => $this->record->status()->transitionToInProgress())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
