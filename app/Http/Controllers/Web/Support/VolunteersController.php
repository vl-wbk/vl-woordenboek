<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Support;

use App\Settings\VolunteerSettings;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class VolunteersController
{
    #[Get(uri: 'ondersteuning/vrijwilligers', name: 'support.volunteers')]
    public function __invoke(): Renderable
    {
        return view('info.volunteers-callout', [
            'pageSettings' => app(VolunteerSettings::class),
        ]);
    }
}
