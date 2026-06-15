<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Notifications\TestNotification;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;

#[Middleware(['auth', 'forbid-banned-user'])]
final readonly class NotificationsController
{
    #[Get(uri: '/meldingen', name: 'notifications:index')]
    public function __invoke(Request $request): Renderable
    {
        $user = auth()->user();
        $tab  = $request->input('tab', 'all');
        $q    = $request->input('zoekterm');

        $query = $user->notifications();
        $user->notify(new TestNotification());

        // Tab filter
        match($tab) {
            'unread'     => $query->whereNull('read_at'),
            'suggesties' => $query->where('data->type', 'suggesties'),
            'kudos'      => $query->where('data->type', 'kudos'),
            'reacties'   => $query->where('data->type', 'reacties'),
            'systeem'    => $query->where('data->type', 'systeem'),
            default      => null,
        };

        // Zoekterm filter
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('data->title', 'like', "%{$q}%")
                    ->orWhere('data->body', 'like', "%{$q}%");
            });
        }

        $notifications = $query->latest()->paginate(7);

        $totalCount   = $user->notifications()->count();
        $unreadCount  = $user->unreadNotifications()->count();
        $kudosCount   = $user->notifications()->where('data->type', 'kudos')->count();

        $typeCounts = [
            'suggesties' => $user->notifications()->where('data->type', 'suggesties')->count(),
            'kudos'      => $kudosCount,
            'reacties'   => $user->notifications()->where('data->type', 'reacties')->count(),
            'systeem'    => $user->notifications()->where('data->type', 'systeem')->count(),
        ];

        return view('notifications.index', compact(
            'notifications',
            'totalCount',
            'unreadCount',
            'kudosCount',
            'typeCounts',
            'user'
        ));
    }

    #[Patch(uri: '/meldingen/{id}/gelezen', name: 'notifications:read')]
    public function read(string $id)
    {
        auth()->user()
            ->notifications()
            ->findOrFail($id)
            ->markAsRead();

        return back();
    }

    #[Patch(uri: '/meldingen/alles-gelezen', name: 'notifications:readAll')]
    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Alle meldingen gemarkeerd als gelezen.');
    }

    #[Delete(uri: '/meldingen/{id}', name: 'notifications:destroy')]
    public function destroy(string $id)
    {
        auth()->user()
            ->notifications()
            ->findOrFail($id)
            ->delete();

        return back();
    }

    #[Delete(uri: '/meldingen', name: 'notifications:destroyAll')]
    public function destroyAll()
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'Alle meldingen verwijderd.');
    }
}
