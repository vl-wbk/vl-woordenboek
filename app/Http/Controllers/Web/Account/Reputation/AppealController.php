<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account\Reputation;

use App\Actions\Account\Reputation\RegisterAppeal;
use App\Http\Requests\StoreAppealRequest;
use App\Models\Appeal;
use App\Models\ReputationLog;
use App\Rules\CanAppealReputation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;

#[Middleware(['auth', 'forbid-banned-user'])]
final readonly class AppealController
{
    #[Get(uri: 'nieuw-beroep', name: 'appeal:create')]
    #[Authorize('create', Appeal::class)]
    public function create(Request $request): Renderable
    {
        $appealedLogIds = auth()->user()
            ->appeals()
            ->pluck('reputation_log_id');

        // Only show negative log entries that haven't been appealed yet
        $reputationLogs = auth()->user()
            ->reputationLogs()
            ->where('type', 'deduction')
            ->whereNotIn('id', $appealedLogIds)
            ->latest()
            ->get();

        return view('account.reputation.appeal', data: [
            'user' => auth()->user(),
            'reputationLogs' => $reputationLogs,
            'appealsThisMonth' => $request->user()->monthlyAppeals
        ]);
    }

    #[Post(uri: 'nieuw-beroep', name: 'appeal:store')]
    public function store(StoreAppealRequest $request, RegisterAppeal $registerAppeal): RedirectResponse
    {
        $registerAppeal($request->user(), $request->getData());

        return back()
            ->with('success', 'Je beroep is ingediend. We laten je binnen 5 werkdagen weten wat de beslissing is.');
    }
}
