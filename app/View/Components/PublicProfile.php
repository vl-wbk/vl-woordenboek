<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\ArticleStates;
use App\Models\{Article, User};
use App\States\Articles\Corrections\ApprovedState;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Eloquent\Builder as DatabaseEloquentBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{Cache, Auth};
use Illuminate\View\Component;
use OwenIt\Auditing\Models\Audit;

/**
 * Component responsible for preparing and rendering a user's public profile page.
 * 
 * This component gathers various user statistics—such as contribution activity over the past year, 
 * total suggestions, approved corrections, and publication counts—while utilizing caching to optimize 
 * performance. It also handles visibility checks for user interactions, like determining whether 
 * a contact relationship can be established by the currently logged-in visitor.
 * 
 * @package App\View\Components
 */
final class PublicProfile extends Component
{
    /**
     * Configuration array for cache time-to-live settings.
     * 
     * @var array{0: int, 1: int}
     */
    protected array $cacheTTL = [10, 60];

    /**
     * Initialize the component with the target user whose profile is being viewed.
     *
     * @param  User $user The profile owner.
     * @return void
     */
    public function __construct(
        public User $user,
    ) {}

    /**
     * Compile the contribution graph data and aggregate all user statistics  before passing them to the public profile view template.
     *
     * @return View
     */
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

    /**
     * Fetch the total number of approved corrections made by the user from the cache,
     * querying the database and formatting the result as a human-readable string if a cache miss occurs.
     *
     * @return string
     */
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

    /**
     * Fetch the total number of published articles/suggestions authored by the user from the cache,
     * computing and formatting the value on a cache miss. 
     *
     * @return string
     */
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

    /**
     * Retrieve the cached overall suggestion count for the user.
     * Generatingand formatting the total count dynamically hen the cache expires. 
     * 
     * @return string
     */
    private function getCachedSuggestionCount(): string
    {
        $cacheKey = 'user_suggestion_count_' . $this->user->id;

        return Cache::remember($cacheKey, $this->cacheTTl(), function (): string {
            return toHumanReadableNumber($this->user->suggestions()->count());
        });
    }

    /**
     * Generate the default Carbon time interval used for the cache expliration times.
     *
     * @return CarbonInterface
     */
    private function cacheTtl(): CarbonInterface
    {
        return now()->addMinutes(5);
    }

    /**
     * Verify hether a contact relationship option should be available for the authenticated visitor. 
     * 
     * Evaluates to true if the currently logged-in user is visiting someone else's profile 
     * and is not yet connected as a contact.
     *
     * @return bool
     */
    private function contactExists(): bool
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        return $authenticatedUser->contacts->doesntContain($this->user) && $authenticatedUser->isNot($this->user);
    }
}
