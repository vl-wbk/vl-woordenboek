<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Support;

use App\Models\VolunteerPosition;
use App\Settings\VolunteerSettings;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class VolunteersController
{
    use AuthorizesRequests;

    #[Get(uri: 'ondersteuning/vrijwilligers', name: 'support.volunteers')]
    public function __invoke(): Renderable
    {
        return view('info.volunteers-callout', data: [
            'pageSettings' => app(VolunteerSettings::class),
            'positions' => VolunteerPosition::where('is_open', true)->paginate()
        ]);
    }

    public function applicationForm(VolunteerPosition $volunteerPosition): Renderable
    {
        $this->authorize('apply', $volunteerPosition);
    }
}
