<?php

declare(strict_types=1);

namespace App\Filament\Resources\Feedback\Actions;

use Filament\Actions\BulkAction;
use App\Enums\FeedbackStatus;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Bulk action to mark selected feedback records as 'Open' or 'Unprocessed'.
 *
 * This action is designed to be used in the Filament table view for feedback records, allowing administrators to
 * efficiently triage multiple selected items by resetting their status to the initial, waiting for review status.
 *
 * @package App\Filament\Resources\Feedback\Actions
 */
final class MarkAsOpenBulkAction extends BulkAction
{
    use CanCustomizeProcess;

    /**
     * Retrieves the translated default name for this bulk action.
     * This name is used internally by Filament and should correspond to a key in the application's localization files.
     *
     * @return string The translatable key for the action's label.
     */
    public static function getDefaultName(): string
    {
        return __('feedback-resource.table.actions.mark-as-bulk-group.open-action.label');
    }

    /**
     * Configures the appearance, icon, notifications, and core logic of the bulk action.
     *
     * The setup process defines:
     *
     * - Color: Set to 'warning' to visually indicate an action that modifies record state.
     * - Icon: Users the 'heroicon-o-document-text' icon.
     * - Notification: Defines a successful notification title using localization.
     * - Cleanup: Ensures selected records are deselected in the UI after execution.
     * - Core Logic: Executes a database update on the selected collection, setting the `status` field to `FeedbackStatus::Unprocessed`.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->color('warning');
        $this->icon('heroicon-o-document-text');

        $this->successNotificationTitle(title: __('feedback-resource.table.actions.mark-as-bulk-group.open-action.notifications.success'));
        $this->deselectRecordsAfterCompletion();

        $this->action(function (): void {
            // Process the collection by updating the 'status' of each selected record.
            $this->process(static fn(Collection $records) => $records->each(fn(Model $record) => $record->update([
                'status' => FeedbackStatus::Unprocessed,
            ])));

            // Trigger the success notification after the process is complete.
            $this->success();
        });
    }
}
