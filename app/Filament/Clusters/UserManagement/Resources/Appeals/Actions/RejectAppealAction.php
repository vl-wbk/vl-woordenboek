<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\Appeals\Actions;

use App\Actions\Reputation\AppealReviewAction;
use App\Models\Appeal;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Override;

final class RejectAppealAction extends Action
{
    protected static Heroicon $actionIcon = Heroicon::OutlinedXCircle;

    #[Override]
    public static function getDefaultName(): ?string
    {
        return 'reject-appeal';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Afwijzen');
        $this->icon(self::$actionIcon);
        $this->color('danger');
        $this->visible(fn (Appeal $record) => $record->status === 'pending');
        $this->requiresConfirmation();
        $this->modalHeading('Beroep afwijzen');
        $this->modalIcon(self::$actionIcon);

        $this->schema(schema: self::getActionForm());
        $this->action(function (Appeal $record, array $data): void {
            app(AppealReviewAction::class)->execute($record, 'rejected', $data['moderator_note'] ?? null);

            Notification::make()
                ->title('Beroep afgewezen')
                ->danger()
                ->send();
        });
    }

    public static function getActionForm(): array
    {
        return [
            Textarea::make('moderator_note')
                ->label('Reden voor afwijzing (optioneel)')
                ->rows(3),
        ];
    }
}
