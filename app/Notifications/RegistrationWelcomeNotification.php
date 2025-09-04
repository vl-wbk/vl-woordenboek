<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\WelcomeNotification\WelcomeNotification;

final class RegistrationWelcomeNotification extends WelcomeNotification implements ShouldQueue
{
    use Queueable;
}
