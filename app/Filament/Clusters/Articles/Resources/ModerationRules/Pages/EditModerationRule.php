<?php

namespace App\Filament\Clusters\Articles\Resources\ModerationRules\Pages;

use App\Filament\Clusters\Articles\Resources\ModerationRules\ModerationRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditModerationRule extends EditRecord
{
    protected static string $resource = ModerationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
        ];
    }
}
