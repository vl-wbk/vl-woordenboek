<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Lottery;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReputationController
{
    #[Get(uri: '/reputatie', name: 'account:reputation', middleware: ['auth', 'forbid-banned-user'])]
    public function __invoke(Request $request): Renderable
    {
        $appealStatus = request('appeal_status');

        $appeals = $request->user()->appeals()
            ->with('reputationLog')
            ->when($appealStatus, fn ($q) => $q->where('status', $appealStatus))
            ->latest()
            ->paginate(5, ['*'], 'appeal_page');

        return view('account.reputation', data: [
            'user' => $request->user(),
            'reputationLogs' => $request->user()->reputationLogs()->latest()->paginate(10),
            'displayFeedbackDialog' => true,
            'appeals' => $appeals,
        ]);
    }
}
