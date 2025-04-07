<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * RegionInformationController provides geographical context for dictionary entries.
 *
 * This invokable controller handles the display of regional information pages that explain the geographical context of dialect words in the Vlaams Woordenboek.
 * It uses attribute-based routing for clean route definitions and returns a dedicated view for region-specific content.
 */
final readonly class RegionInformationController
{
    /**
     * Renders the region informâtion page.
     *
     * This method serves the static content explaining the geographical regions used throughouyt the dictionary.
     * This view contains maps and descriptions of the various Flemish regions and their dialect characteristics.
     *
     * @return Renderable The view containing regional information.
     */
    #[Get(uri: 'regio-informatie', name: 'definitions.region-info')]
    public function __invoke(): Renderable
    {
        return view('region-information');
    }
}
