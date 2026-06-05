<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Support;

use App\Actions\Support\StoreVolunteerApplication;
use App\Http\Requests\Support\StoreVolunteerApplicationRequest;
use App\Models\Region;
use App\Models\VolunteerPosition;
use App\Settings\VolunteerSettings;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final readonly class VolunteersController
{
    use AuthorizesRequests;

    #[Get(uri: "ondersteuning/vrijwilligers", name: "support.volunteers")]
    public function __invoke(): Renderable
    {
        abort_if(!app(VolunteerSettings::class)->pageActive, Response::HTTP_NOT_FOUND);

        return view(
            "info.volunteers-callout",
            data: [
                "pageSettings" => app(VolunteerSettings::class),
                "positions" => VolunteerPosition::where("is_open", true)->paginate(),
            ],
        );
    }

    #[Get(uri: "vrijwilligers/aanmelden/{volunteerPosition}", name: "volunteers.apply", middleware: ["auth"])]
    public function applicationForm(Request $request, VolunteerPosition $volunteerPosition): Renderable
    {
        $this->authorize("apply", $volunteerPosition);

        return view(
            "volunteers.apply",
            data: [
                "user" => $request->user(),
                "regions" => Region::all(),
                "position" => $volunteerPosition,
            ],
        );
    }

    /**
     *
     * @throws InvalidDataClass  when the Data Transef Object contains invalid data.
     * @throws Throwable         when the volunteer application couldn't be stored successgfully
     */
    #[Post(uri: "vrijwilligers/aanmelden/{volunteerPosition}", name: "volunteers.apply.store", middleware: ["auth"])]
    public function store(
        StoreVolunteerApplicationRequest $storeVolunteerApplicationRequest,
        VolunteerPosition $volunteerPosition,
        StoreVolunteerApplication $storeVolunteerApplication,
    ): RedirectResponse {
        $storeVolunteerApplication($volunteerPosition, $storeVolunteerApplicationRequest->getData());
        flash(text: "We hebben je aanmelding goed ontvangen, we kijken er spoedig naar!");

        return to_route("volunteers.apply", $volunteerPosition);
    }
}
