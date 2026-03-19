<?php

namespace App\Providers\Filament;

use A909M\FilamentStateFusion\Actions\StateFusionAction;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class ConfigurationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->developmentActionButtonConfigurations();

        Table::configureUsing(function (Table $table): void {
            $table
                ->striped()
                ->deferLoading()
                ->persistFiltersInSession()
                ->persistSortInSession()
                ->persistSearchInSession();
        });

        StateFusionAction::configureUsing(function (StateFusionAction $stateFusionAction): void {
            $stateFusionAction->modal(false);
        });
    }

    private function developmentActionButtonConfigurations(): void
    {
        FactoryAction::configureUsing(function (FactoryAction $action): void {
            $action->color('gray')
                ->icon(Heroicon::OutlinedCog8Tooth)
                ->modalIcon(Heroicon::OutlinedCog8Tooth)
                ->modalSubmitActionLabel('Genereren')
                ->modalIconColor('primary')
                ->hiddenLabel()
                ->modalCloseButton(false);
        });
    }
}
