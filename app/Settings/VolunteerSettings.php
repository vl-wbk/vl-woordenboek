<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

final class VolunteerSettings extends Settings
{
    public bool $pageActive = false;

    public bool $pageSelectionProcedureActive = false;

    public bool $pageRegistrationActive = false;

    public array $procedure = [];

    public ?string $questionsEmail = null;

    public ?string $pageTitle = null;
    public ?string $pageTagLine = null;

    public ?string $questionsTitle = null;

    public ?string $questionsContent = null;

    public ?string $whyHelpTitle = null;

    public ?string $whyHelpContent = null;

    public static function group(): string
    {
        return 'volunteers';
    }
}
