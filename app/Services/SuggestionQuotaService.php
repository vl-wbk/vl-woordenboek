<?php 

declare(strict_types=1);

namespace App\Services;

use App\Models\Article; 
use Illuminate\Http\Request; 
use Illuminate\Support\Carbon;

final class SuggestionQuotaService
{
    public function isLimitReached(Request $request): bool 
    {
        return $this->currentAmount($request) >= $this->maxAllowedAttempts($request);
    }

    public function nextReset(Request $request): ?Carbon
    {
        $config = $this->configurationFor($request);
        $since = Carbon::now()->subHours($config['window']);

        $query = Article::query()
            ->where('created_at', '>=', $since)
            ->orderBy('created_at')
            ->limit(1);

        if ($request->user()) {
            $query->where('author_id', $request->user()->id);
        } else {
            $query->whereNull('author_id')->where('ip_address', $request->ip());
        }

        $oudste = $query->first();

        return $oudste
            ? $oudste->created_at->addHours($config['window'])
            : null;
    }

    public function currentAmount(Request $request): int 
    {
        $config = $this->configurationFor($request); 
        $since = Carbon::now()->subHours($config['window']);

        $query = Article::query()->where('created_at', '>=', $since);

        if ($request->user()) {
            $query->where('author_id', $request->user()->id);
        } else {
            $query->whereNull('author_id')->where('ip_address', $request->ip());
        }

        return $query->count();
    }

    public function maxAllowedAttempts(Request $request): int 
    {
        return $this->configurationFor($request)['max'];
    }

    public function remaining(Request $request): int 
    {
        return max(0, $this->maxAllowedAttempts($request) - $this->currentAmount($request));
    }

    private function configurationFor(Request $request): array 
    {
        return $request->user()
            ? config('flemish-dictionary.rate-limiting.suggestions.authenticated')
            : config('flemish-dictionary.rate-limiting.suggestions.anonymous');
    }
}