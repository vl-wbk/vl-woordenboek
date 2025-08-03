<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Kenepa\ResourceLock\Resources\LockResource as ResourcesLockResource;

final class LockResource extends ResourcesLockResource implements HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
        return ['unlock_resource'];
    }
}
