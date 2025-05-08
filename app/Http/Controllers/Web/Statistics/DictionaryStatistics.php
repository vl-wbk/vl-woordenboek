<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Statistics;

use App\Http\Controllers\Controller;
use App\Services\StatisticService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

final class DictionaryStatistics extends Controller
{
    #[Get(uri: 'statistieken', name: 'statistics', middleware: ['throttle:global'])]
    public function __invoke(): Renderable
    {
        $statistics = new StatisticService();

        return view('statistics.index', [
            'articleViews' => $statistics->getArticleViews(),
            'articleCount' => $statistics->getArticleCount(),
            'editCount' => $statistics->getEditCount(),
            'getUserCount' => $statistics->getUserCount(),
            'getVolunteerCount' => $statistics->getVolunteerCount(),
            'getRegisteredToday' => $statistics->registeredToday(),
            'userRegistrations' => $statistics->userRegistrationChartData(),
            'articleChart' => $statistics->articleChartData(),
        ]);
    }
}
