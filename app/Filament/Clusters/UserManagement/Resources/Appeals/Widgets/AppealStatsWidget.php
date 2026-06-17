<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\Appeals\Widgets;

use App\Models\Appeal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class AppealStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total    = Appeal::count();
        $pending  = Appeal::where('status', 'pending')->count();
        $approved = Appeal::where('status', 'approved')->count();
        $rejected = Appeal::where('status', 'rejected')->count();

        $approvalRate = $total > 0
            ? round(($approved / $total) * 100)
            : 0;

        return [
            Stat::make('Openstaand', $pending)
                ->description('Wacht op beoordeling')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Toegekend', $approved)
                ->description("{$approvalRate}% van totaal")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Afgewezen', $rejected)
                ->description('Beroepen afgewezen')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('Totaal', $total)
                ->description('Alle beroepen')
                ->descriptionIcon('heroicon-o-scale')
                ->color('gray'),
        ];
    }
}
