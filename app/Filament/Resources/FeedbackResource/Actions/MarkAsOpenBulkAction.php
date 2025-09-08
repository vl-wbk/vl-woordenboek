<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeedbackResource\Actions;

use App\Enums\FeedbackStatus;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class MarkAsOpenBulkAction extends BulkAction
{
    use CanCustomizeProcess;

    public static function getDefaultName(): string
    {
        return __('feedback-resource.table.actions.mark-as-bulk-group.open-action.label');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('warning');
        $this->icon('heroicon-o-document-text');

        $this->successNotificationTitle(title: __('feedback-resource.table.actions.mark-as-bulk-group.open-action.notifications.success'));
        $this->deselectRecordsAfterCompletion();

        $this->action(function (): void {
            $this->process(static fn(Collection $records) => $records->each(fn(Model $record) => $record->update([
                'status' => FeedbackStatus::Unprocessed,
            ])));

            $this->success();
        });
    }
}
