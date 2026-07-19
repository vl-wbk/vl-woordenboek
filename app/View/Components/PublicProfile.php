<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Builders\ArticleBuilder;
use App\Enums\ArticleStates;
use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use OwenIt\Auditing\Models\Audit;
use stdClass;

final class PublicProfile extends Component
{
    /**
     * @var array{0: int, 1: int}
     */
    protected array $cacheTTL = [10, 60];

    public function __construct(
        public User $user,
    ) {}

    public function render(): View
    {
        $contributionData = Audit::where('user_id', $this->user->id)
            ->where('auditable_type', Article::class)
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->where('created_at', '>=', now()->subYear())
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'date')
            ->toArray();

        return view('components.public-profile', data: [
            'user' => $this->user,
            'suggestionCount' => $this->getCachedSuggestionCount(),
            'contactExist' => $this->contactExists(),
            'contributionData' => $contributionData
        ]);
    }

    private function getCachedPublicationCount(): int 
    {
        $cacheKey = 'user_publication_count_' . $this->user->id;

    }

    private function getCachedSuggestionCount(): string
    {
        $cacheKey = 'user_suggestion_count_' . $this->user->id;
        $cacheTtl = now()->addMinutes(5);

        return Cache::remember($cacheKey, $cacheTtl, function (): string {
            return toHumanReadableNumber($this->user->suggestions()->count());
        });
    }

    private function contactExists(): bool
    {
        return auth()->user()->contacts->doesntContain($this->user) && auth()->user()->isNot($this->user);
    }
}
