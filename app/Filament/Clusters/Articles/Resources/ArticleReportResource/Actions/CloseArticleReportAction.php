<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions;

use App\States\Reporting\Status;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * Represents the action for closing an article report.
 *
 * The `CloseArticleReportAction` class defines the logic and configuration for handling the closure of an article report.
 * This action transitions the report's state to "Closed" and ensures that only authorized users can perform the closure.
 *
 * This action is integrated into the Filament admin panel and provides a user-friendly interface for managing article reports.
 * It includes visual indicators, such as an icon and label, and displays success or failure notifications based on the outcome of the closure process.
 *
 * @package App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions
 */
final class CloseArticleReportAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Returns the default name for the action.
     *
     * The default name is used to identify the action within the Filament admin panel.
     * In this case, the name is set to "close-report."
     *
     * @return string|null The default name of the action.
     */
    public static function getDefaultName(): ?string
    {
        return 'close-report';
    }

    /**
     * Configures the action's behavior and appearance.
     *
     * This method sets up the action's icon, color, label, authorization, and notifications.
     * It also defines the logic for transitioning the report's state to "Closed."
     *
     * - The icon is dynamically retrieved from the "Closed" state.
     * - The authorization ensures that only users with the "markAsClosed" permission can perform the action.
     * - Success and failure notifications are displayed based on the outcome of the action.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->icon(Status::Closed->getIcon());
        $this->color('gray');
        $this->label('melding afsluiten');
        $this->authorize('markAsClosed', $this->record);

        $this->successNotificationTitle('Het ticket is successvol afgesloten');
        $this->failureNotificationTitle('Helaas konden we het ticket niet afsluiten wegens een technische fout');

        $this->action(function (): void {
            if ($this->process(fn (): bool => $this->record->status()->transitionToClosed())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
