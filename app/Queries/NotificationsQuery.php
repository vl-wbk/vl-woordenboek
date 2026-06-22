<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\User;
use Filament\Notifications\DatabaseNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

final readonly class NotificationsQuery
{
    public function __construct(
        protected User $user,
    ) {}

    public function getPaginated(string $tab, ?string $search): LengthAwarePaginator
    {
        return $this->applyFilters($this->user->notifications()->where('type', '!=', DatabaseNotification::class), $tab, $search)
            ->latest()
            ->paginate(7);
    }

    public function getCounts(): array
    {
        return [
            'all'        => $this->user->notifications()->where('type', '!=', DatabaseNotification::class)->count(),
            'unread'     => $this->user->unreadNotifications()->where('type', '!=', DatabaseNotification::class)->count(),
            'suggesties' => $this->user->notifications()->where('type', '!=', DatabaseNotification::class)->where('data->type', 'suggesties')->count(),
            'systeem'    => $this->user->notifications()->where('type', '!=', DatabaseNotification::class)->where('data->type', 'systeem')->count(),
        ];
    }

    public function getTabs(): array
    {
        return [
            ['key' => 'all',        'label' => 'Alle'],
            ['key' => 'unread',     'label' => 'Ongelezen'],
            ['key' => 'suggesties', 'label' => 'Suggesties'],
            ['key' => 'systeem',    'label' => 'Systeem'],
        ];
    }

    public function getTypeConfig(): array
    {
        return [
            'suggesties' => ['iconClass' => 'ic-blue', 'badgeClass' => 'nb-blue', 'label' => 'Suggestie'],
            'contact'    => ['iconClass' => 'ic-red',  'badgeClass' => 'nb-red',  'label' => 'Contact'],
            'systeem'    => ['iconClass' => 'ic-gray', 'badgeClass' => 'nb-gray', 'label' => 'Systeem'],
        ];
    }

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
