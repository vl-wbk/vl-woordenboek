<?php 

declare(strict_types=1);

namespace App\Filament\Resources\Users\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Override;

final class RestrictFeatureBulkAction extends Action 
{
    #[Override]
    public static function getDefaultName(): ?string
    {
        return 'ban-selected';
    }

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Beperk functionaliteit');
        $this->icon(Heroicon::OutlinedNoSymbol);
        $this->color('danger');
        $this->schema(schema: $this->configureFormLayout());

        $this->action(function (\Illuminate\Support\Collection $records, array $data) {
            $records->each(fn (User $user) => $user->featureBans()->updateOrCreate(
                ['feature' => $data['feature']],
                ['reason' => $data['reason'] ?? null, 'expires_at' => $data['expires_at'] ?? null],
            ));
    
           Notification::make()->title($records->count() . ' users banned')->success()->send();
        })
        ->deselectRecordsAfterCompletion();

    }

    private function configureFormLayout(): array 
    {
        return [
            Select::make('feature')
                ->label('Functionaliteit')
                ->options(config('flemish-dictionary.features.bannable'))
                ->required(),

            Textarea::make('reason')
                ->label('Motivatie')
                ->required(),

            DateTimePicker::make('expires_at')
                ->after('now'),
        ];
    }
}