<?php

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages;

use App\Filament\Clusters\Articles\Resources\EtymologyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewEtymology extends ViewRecord
{
    protected static string $resource = EtymologyResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('heroicon-s-trash'),
        ];
    }
}
