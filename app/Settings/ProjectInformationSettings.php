<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

final class ProjectInformationSettings extends Settings
{
    public bool $pageActive = false;
    public string $pageTitle = 'Porject informatie';
    public ?string $pageContent = null;

    public static function group(): string
    {
        return 'projectInformation';
    }
}
