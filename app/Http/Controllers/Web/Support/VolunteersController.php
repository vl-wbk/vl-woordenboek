<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Support;

use App\Enums\VolunteerPositions;
use App\Settings\VolunteerSettings;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Middleware\Middleware;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @todo Implement docblocks for the controller.
 */
final readonly class VolunteersController
{
    #[Get(uri: 'ondersteuning/vrijwilligers', name: 'support.volunteers')]
    public function __invoke(): Renderable
    {
        return view('info.volunteers-callout', [
            'pageSettings' => app(VolunteerSettings::class),
        ]);
    }
	
	
	#[Get(uri: 'ondersteuning/vrijwilligers/registreren/{volunteerPositions}', name: 'support.volunteers.submit', middleware: ['auth'])]
	public function apply(int $volunteerPositions): Renderable
	{
		return view('info.volunteers.application', data: [
            'position' => VolunteerPositions::tryFrom($volunteerPositions)
		]);
	}

    #[Post(uri: 'ondersteuning/vrijwilligers/aanmelding', name: 'support.volunteers.store', middleware: ['auth'])]
    public function store(): RedirectResponse
    {

    }
}
