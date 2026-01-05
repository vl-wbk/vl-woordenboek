<?php

declare(strict_types=1);

namespace App\Filament\Resources\Feedback\Actions;

use Filament\Actions\BulkAction;
use App\Enums\FeedbackStatus;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Bulk action for marking selected feedback records ad 'Closed' or 'Processed'.
 *
 * This action allows a user to perform a batch operation directly from the Filament table,
 * updating the status of multiple records to ensure they are marked as handled.
 *
 * @package App\Filament\Resources\Feedback\Actions
 */
final class MarkAsClosedBulkAction extends BulkAction
{
    use CanCustomizeProcess;

    /**
     * Retrieves the default name for the bulk action;
     *
     * This name is typically used as the array key when defining actions and should return the label displayed
     * to the user, usually retrieved via a localization key.
     *
     * @return string The translated label for the action.
     */
    public static function getDefaultName(): string
    {
        return __('feedback-resource.table.actions.mark-as-bulk-group.close-action.label');
    }

    /**
     * Sets up the configuration for the bulk action.
     *
     * This method is used to define the visual appearance, icons, success notifications,
     * and the primary logic (the action itself).
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->color('success');
        $this->icon('heroicon-o-document-check');
        $this->authorizeIndividualRecords('mark-as-closed');

        $this->successNotificationTitle(title: __('feedback-resource.table.actions.mark-as-bulk-group.close-action.notifications.success'));
        $this->deselectRecordsAfterCompletion();

        $this->action(function (): void {
            $this->process(static fn(Collection $records) => $records->each(fn(Model $record) => $record->update([
                'status' => FeedbackStatus::Processed,
            ])));

            $this->success();
        });
    }
}
