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

final class ApproveAppealAction extends Action
{
    protected static Heroicon $actionIcon = Heroicon::OutlinedCheckCircle;

    #[Override]
    public static function getDefaultName(): string
    {
        return 'approve-appeal';
    }

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Toekennen');
        $this->icon(self::$actionIcon);
        $this->color('success');
        $this->visible(fn (Appeal $record): bool => $record->status === 'pending');
        $this->requiresConfirmation();
        $this->modalHeading('Beroep toekennen');
        $this->modalDescription('De reputatiewijziging wordt teruggedraaid en de gebruiker wordt op de hoogte gesteld.');
        $this->modalIcon(self::$actionIcon);

        $this->schema(
            $this->getActionForm()
        );

        $this->action(function (Appeal $record, array $data): void {
            app(AppealReviewAction::class)->execute($record, 'approved', $data['moderator_note'] ?? null);

            Notification::make()
                ->title('Beroep toegekend')
                ->success()
                ->send();
        });
    }

    protected function getActionForm(): array
    {
        return [
            Textarea::make('moderator_note')
                ->label('Notitie voor de gebruiker (optioneel)')
                ->rows(3),
        ];
    }
}
