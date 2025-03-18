<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\BanResource\Pages;

use App\Filament\Clusters\UserManagement\Resources\BanResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

final class ListBans extends ListRecords
{
    protected static string $resource = BanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Documentatie')
                ->color('gray')
                ->icon('tabler-book')
                ->url('https://www.google.com')
                ->openUrlInNewTab(),
        ];
    }
}
