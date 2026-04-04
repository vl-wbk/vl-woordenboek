<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Concepts;

use App\Models\Concept;
use Spatie\RouteAttributes\Attributes\Get;
use Symfony\Component\HttpFoundation\RedirectResponse;

final readonly class DeleteController
{
    #[Get(uri: 'verwijder-concept/{concept}', name: 'concepts:delete', middleware: ['auth', 'forbid-banned-user'])]
    public function __invoke(Concept $concept): RedirectResponse
    {
        throw new \LogicException('needs implementation');
    }
}
