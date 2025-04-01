<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReportResource\Actions;

use Filament\Tables;
use Filament\Tables\Actions\Action;

final readonly class TableActionsConfiguration
{
    public static function headerActions(): array
    {
        return [
            Action::make('Help')
                ->icon('heroicon-o-lifebuoy')
                ->color('gray')
        ];
    }

    public static function rowActions(): array
    {
        return [
            ViewAction::make(),
            Tables\Actions\DeleteAction::make()->hiddenLabel(),
        ];
    }

    public static function bulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
