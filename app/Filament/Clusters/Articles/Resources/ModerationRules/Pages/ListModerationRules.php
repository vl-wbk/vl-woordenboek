<?php

namespace App\Filament\Clusters\Articles\Resources\ModerationRules\Pages;

use App\Filament\Clusters\Articles\Resources\ModerationRules\ModerationRuleResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListModerationRules extends ListRecords
{
    protected static string $resource = ModerationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('documentation')
                ->label('Help')
                ->icon(Heroicon::OutlinedLifebuoy)
                ->color('primary')
                ->url('https://vl-wbk.github.io/documentatie/artikelen/taaladviezen', shouldOpenInNewTab: true),

            ActionGroup::make([
                CreateAction::make()
                    ->color('gray')
                    ->icon(Heroicon::OutlinedPlusCircle)
                    ->label('Advies toevoegen'),
            ])->buttonGroup()
        ];
    }
}
