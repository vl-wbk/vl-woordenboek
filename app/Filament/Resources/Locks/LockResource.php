<?php

declare(strict_types=1);

namespace App\Filament\Resources\Locks;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Kenepa\ResourceLock\Resources\LockResource as ResourcesLockResource;

/**
 * @todo Document this resource
 */
final class LockResource extends ResourcesLockResource implements HasShieldPermissions
{
    /**
     * @return list<string>
     */
    public static function getPermissionPrefixes(): array
    {
        return ['unlock_resource'];
    }
}
