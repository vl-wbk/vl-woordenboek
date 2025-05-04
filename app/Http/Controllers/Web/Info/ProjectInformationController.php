<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Info;

use App\Settings\ProjectInformationSettings;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ProjectInformationController
{
    #[Get(uri: 'project-informatie', name: 'project-information')]
    public function __invoke(): Renderable
    {
        $settings = app(ProjectInformationSettings::class);
        abort_if(! $settings->pageActive, Response::HTTP_NOT_FOUND);

        return view('info.settings-page', [
            'pageSettings' => $settings,
        ]);
    }
}
