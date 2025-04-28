<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

final class VolunteerSettings extends Settings
{
    public bool $pageActive = false;
    public ?string $pageTitle = null;
    public ?string $pageContent = null;
    public array $positions = [];

    public static function group(): string
    {
        return 'volunteers';
    }
}
