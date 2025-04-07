<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Legal;

use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * TermsOfServiceController serves the terms of service page for the Vlaams Woordenboek.
 *
 * This invokable controller provides access to the platform's terms of service through a dedicated route.
 * It uses attribute-based routing for clean route definitions and returns a static view containing the legal terms and conditions that
 * govern the use of the dictionary platform.
 */
final readonly class TermsOfServiceController
{
    /**
     * Renders the terms of service page.
     *
     * This method serves the static content containing the platform's terms and conditions.
     * The view presents the legal requirements, user obligations, and platform policies in a structured format.
     *
     * @return Renderable The view containing terms of service content
     */
    #[Get(uri: 'voorwaarden', name: 'terms-of-service')]
    public function __invoke(): Renderable
    {
        return view('info.terms');
    }
}
