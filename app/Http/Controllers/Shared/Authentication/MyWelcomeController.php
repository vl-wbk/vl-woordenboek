<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shared\Authentication;

use Spatie\WelcomeNotification\WelcomeController;
use Symfony\Component\HttpFoundation\Response;

final class MyWelcomeController extends WelcomeController
{
    public function sendPasswordSavedResponse(): Response
    {
        return redirect()->route('filament.admin.pages.dashboard');
    }
}
