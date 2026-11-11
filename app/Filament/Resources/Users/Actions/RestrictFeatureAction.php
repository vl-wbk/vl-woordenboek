<?php 

declare(strict_types=1); 

namespace App\Filament\Resources\Users\Actions;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Override;

final class RestrictFeatureAction extends Action
{
    public static function getDefaultName(): string 
    {
        return 'restrict-feature';
    }

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Beperk functionaliteiten');
        $this->icon(Heroicon::OutlinedNoSymbol);
        $this->color('danger');
     
        $this->schema(schema: $this->configureFormLayout()); 
        $this->action(fn (User $record, array $array) => $this->performAction($record, $array));
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
                ->rows(5)
                ->required(),

            DateTimePicker::make('expires_at')
                ->helperText('Empty = permanent')
                ->after('now'),
        ];
    }

    private function performAction(User $record, array $data): void 
    {
        $record->featureBans()->updateOrCreate(
            ['feature' => $data['feature']],
            ['reason' => $data['reason'] ?? null, 'expires_at' => $data['expires_at'] ?? null],
        );

        Notification::make()
            ->title('De gebruiker is beperkt in zijn functionaliteiten')
            ->success()
            ->send();
    }
}