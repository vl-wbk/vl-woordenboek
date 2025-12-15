<?php

namespace App\Filament\Clusters\Articles\Resources\ModerationRules\Pages;

use App\Filament\Clusters\Articles\Resources\ModerationRules\ModerationRuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateModerationRule extends CreateRecord
{
    protected static string $resource = ModerationRuleResource::class;
}
