<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\User;
use Filament\Notifications\DatabaseNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Service for orchestrating notification queries for the current user.
 *
 * This class abstracts the logic required to query, filter, and categorize notifications.
 * It serves as the primary data source for the notification dashboard, ensuring consistency in how counts and lists are presented.
 *
 * @internal This class is intended for internal use within the application's notification module and should not be extended by consumers.
 * @package  App\Queries
 */
final readonly class NotificationsQuery
{
    /**
     * Initializes the query service for the provided user.
     *
     * The injected User model serves as the root for all notification relations.
     * All subsequent query operations, counting, and filtering are scoped exclusively to this user's notification collection.
     *
     * @param  User $user The model instance representing the owner of the notifications.
     * @return void
     */
    public function __construct(
        protected User $user,
    ) {}

    /**
     * Retrieves a paginated collection of notifications based on provided criteria.
     *
     * This method acts as the entry point for fetching notifications for the UI.
     * It performs three main tasks:
     *
     * 1. It initiates the query from the user's notifications, excluding system-specific types.
     * 2. It applies filters based on the selected category (tab) and any provided search text.
     * 3. It enforces an ordering by the newest entries and enforces a pagination limit of 7 items per page.
     *
     * @see getTabs()
     *
     * @param  string       $tab    The filter category (e.g., 'all', 'unread'). See getTabs() for valid keys.
     * @param  string|null  $search Optional keyword string. If provided, the query will return notifications where either the title or the body matches the keyword.
     * @return LengthAwarePaginator<int, DatabaseNotification>
     */
    public function getPaginated(string $tab, ?string $search): LengthAwarePaginator
    {
        return $this->applyFilters($this->user->notifications()->where('type', '!=', DatabaseNotification::class), $tab, $search)
            ->latest()
            ->paginate(7);
    }

    /**
     * Aggregates rhe count of notifications for each category.
     *
     * This method is responsible for providing the data required to populate numeric badges on UI tabs.
     * It calculates totals indendently for each category to ensure that the dashboard can accurately display the count of available notifications per filter.
     *
     * @return array<string, int>
     */
    public function getCounts(): array
    {
        return [
            'all'        => $this->user->notifications()->where('type', '!=', DatabaseNotification::class)->count(),
            'unread'     => $this->user->unreadNotifications()->where('type', '!=', DatabaseNotification::class)->count(),
            'suggesties' => $this->user->notifications()->where('type', '!=', DatabaseNotification::class)->where('data->type', 'suggesties')->count(),
            'systeem'    => $this->user->notifications()->where('type', '!=', DatabaseNotification::class)->where('data->type', 'systeem')->count(),
        ];
    }

    /**
     * Returns the configuration structure for the notification dashboard tabs.
     *
     * This acts as the single source of truth for the tabbed navigation.
     * It maps machine-readable keys to human-readable labels, allowing the UI to loop through this array to render
     * the tabs consistently without hardcoding values in the views.
     *
     * @return list<array{key: string, label: string}>
     */
    public function getTabs(): array
    {
        return [
            ['key' => 'all',        'label' => 'Alle'],
            ['key' => 'unread',     'label' => 'Ongelezen'],
            ['key' => 'suggesties', 'label' => 'Suggesties'],
            ['key' => 'systeem',    'label' => 'Systeem'],
        ];
    }

    /**
     * Returns the styling configuration for specific notification types.
     *
     * This method provides a dictionary that the UI uses to determine the visual presentation of a notification.
     * By Looking up a notification's type here. The system can retrieve the correct icon and badge CSS classes
     * along with the appropriate label to show to the end user.
     *
     * @return array<string, array{badgeClass: string, iconClass: string, label: string}>
     */
    public function getTypeConfig(): array
    {
        return [
            'suggesties' => ['iconClass' => 'ic-blue', 'badgeClass' => 'nb-blue', 'label' => 'Suggestie'],
            'contact'    => ['iconClass' => 'ic-red',  'badgeClass' => 'nb-red',  'label' => 'Contact'],
            'systeem'    => ['iconClass' => 'ic-gray', 'badgeClass' => 'nb-gray', 'label' => 'Systeem'],
        ];
    }


    /**
     * Applies dynamic filtering logic to the notification query builder.
     * This is an internal helper that modifies an existing query builder instance by applying two types of constraints:
     *
     * 1. Category Filtering: if a specific tab is selected, it restricts the results based on read status (for unread) or the notification data type.
     * 2. Keyword search: if a search string is provided, it performs a case-insentive 'like' search across both the notification title and body.
     *
     * @param  MorphMany<DatabaseNotification> $query  The base Eloquent query builder.
     * @param  string                          $tab    The current filter tab selected.
     * @param  string|null                     $search The search term used for filtering content.
     * @return MorphMany<DatabaseNotification>
     */
    protected function applyFilters(MorphMany $query, string $tab, ?string $search): MorphMany
    {
        if ($tab === 'unread') {
            $query->whereNull('read_at');
        } elseif (in_array($tab, ['suggesties', 'systeem'])) {
            $query->where('data->type', $tab);
        }

        if ($search) {
            $query->where(fn ($sub) =>
                $sub->where('data->title', 'like', "%{$search}%")
                    ->orWhere('data->body', 'like', "%{$search}%")
            );
        }

        return $query;
    }
}
