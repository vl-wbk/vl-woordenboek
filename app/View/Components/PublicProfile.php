<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Builders\ArticleBuilder;
use App\Builders\UserBuilder;
use App\Enums\ArticleStates;
use App\Models\Article;
use App\Models\User;
use App\States\Articles\Corrections\ApprovedState;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Eloquent\Builder as DatabaseEloquentBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
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

        $contributionData = Audit::query()
            ->where('user_id', $this->user->id)
            ->where('auditable_type', Article::class)
            ->where('created_at', '>=', now()->subYear()->startOfWeek(\Carbon\Carbon::MONDAY))
            ->where('created_at', '<=', now())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return view('components.public-profile', data: [
            'user' => $this->user,
            'suggestionCount' => $this->getCachedSuggestionCount(),
            'correctionsCount' => $this->getCachedCorrectionsCount(),
            'publications' => $this->getCachedPublicationCount(),
            'contactExist' => $this->contactExists(),
            'contributionData' => $contributionData
        ]);
    }

    public function getCachedCorrectionsCount(): string
    {
        $cacheKey = 'user_correction_count_' . $this->user->id;

        $user = $this->user
            ->newQuery()
            ->withCount(['corrections' => function (DatabaseEloquentBuilder $query): void {
                $query->whereState('state', ApprovedState::class);
            }])->first();

        return Cache::remember($cacheKey, $this->cacheTTL(), fn (): string => toHumanReadableNumber($user->corrections_count));
    }

    private function getCachedPublicationCount(): string
    {
        $cacheKey = 'user_publication_count_' . $this->user->id;

        $user = $this->user
            ->newQuery()
            ->where('id', $this->user->id)->withCount(['suggestions' => function (DatabaseEloquentBuilder $query): void {
                $query->where('articles.state', ArticleStates::Published);
            }])->first();


        return Cache::remember($cacheKey, $this->cacheTTL(), fn (): string => toHumanReadableNumber($user->suggestions_count));
    }

    private function getCachedSuggestionCount(): string
    {
        $cacheKey = 'user_suggestion_count_' . $this->user->id;

        return Cache::remember($cacheKey, $this->cacheTTl(), function (): string {
            return toHumanReadableNumber($this->user->suggestions()->count());
        });
    }

    private function cacheTtl(): CarbonInterface
    {
        return now()->addMinutes(5);
    }

    private function contactExists(): bool
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        return $authenticatedUser->contacts->doesntContain($this->user) && $authenticatedUser->isNot($this->user);
    }
}
