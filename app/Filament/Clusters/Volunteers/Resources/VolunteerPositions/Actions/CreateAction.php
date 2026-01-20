<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Actions;

use Filament\Actions\CreateAction as BaseCreateAction;
use Filament\Support\Icons\Heroicon;

final class CreateAction extends BaseCreateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->icon(icon: Heroicon::OutlinedPlusCircle);
        $this->color(color: 'gray');
    }
}
