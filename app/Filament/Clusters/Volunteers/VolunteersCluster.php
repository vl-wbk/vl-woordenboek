<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers;

use App\Filament\Support\Concerns\HasActiveIcon;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

final class VolunteersCluster extends Cluster
{
    use HasActiveIcon;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
}
