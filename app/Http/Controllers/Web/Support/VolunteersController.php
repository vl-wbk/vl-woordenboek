<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Support;

use App\Settings\VolunteerSettings;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Middleware\Middleware;
use Spatie\RouteAttributes\Attributes\Get;

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
	
	
	#[Get(uri: 'ondersteuning/vrijwilligers/registreren', name: 'support.volunteers.submit', middleware: ['auth'])]
	public function apply(?string $role = null): Renderable
	{
		return view('info.volunteers.appli cation',[
		
		]);
	}
}
