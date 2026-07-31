<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\SuggestionQuotaService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureSuggestioneQuotumNotExceeded
{
    public function __construct(
        private readonly SuggestionQuotaService $quota
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->quota->isLimitReached($request)) {
            $message = 'Je hebt de limiet bereikt voor het indienen van suggesties. Probeer het later opnieuw of meld je aan bij de redactie.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 429);
            }

            return back()
                ->withInput()
                ->withErrors(['quotum' => $message]);
        }

        return $next($request);
    }
}
