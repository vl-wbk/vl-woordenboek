<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Notifications\TestNotification;
use App\Queries\NotificationsQuery;
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
        $queryService = new NotificationsQuery($request->user());

        return view('notifications.index', [
            'notifications' => $queryService->getPaginated(
                $request->input('tab', 'all'),
                $request->input('zoekterm')
            ),
            'tabCounts'     => $queryService->getCounts(),
            'tabs'          => $queryService->getTabs(),
            'typeConfig'    => $queryService->getTypeConfig(),
        ]);
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
