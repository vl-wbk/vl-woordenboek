<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account\Reputation;

use App\Models\ReputationLog;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;

#[Middleware(['auth', 'forbid-banned-user'])]
final readonly class AppealController
{
    #[Get(uri: 'nieuw-beroep', name: 'appeal:create')]
    public function create(): Renderable
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

        $appealsThisMonth = auth()->user()
            ->appeals()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        abort_if($appealsThisMonth >= 3, 403, 'Maandelijkse limiet bereikt.');

        return view('account.reputation.appeal', data: [
            'user' => auth()->user(),
            'reputationLogs' => $reputationLogs,
            'appealsThisMonth' => $appealsThisMonth
        ]);
    }

    #[Post(uri: 'nieuw-beroep', name: 'appeal:store')]
    public function store(Request $request): RedirectResponse
    {
        $appealsThisMonth = auth()->user()
            ->appeals()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        abort_if($appealsThisMonth >= 3, 429, 'Maandelijkse limiet bereikt.');

        $request->validate([
    'reputation_log_id' => [
        'required',
        'exists:reputation_logs,id',
        function ($attribute, $value, $fail) {
            $log = ReputationLog::find($value);

            if (!$log || $log->user_id !== auth()->id()) {
                $fail('Deze registratie bestaat niet.');
                return;
            }

            if ($log->type !== 'deduction') {
                $fail('Je kan alleen negatieve aanpassingen aanvechten.');
                return;
            }

            $alreadyAppealed = auth()->user()
                ->appeals()
                ->where('reputation_log_id', $value)
                ->exists();

            if ($alreadyAppealed) {
                $fail('Je hebt dit al aangevochten.');
            }
        },
    ],
    'reason' => ['required', 'string', 'min:20', 'max:500'],
], [
    'reputation_log_id.required' => 'Kies een reputatiewijziging om aan te vechten.',
    'reason.required'            => 'Geef een reden op.',
    'reason.min'                 => 'Je reden is te kort (min. 20 tekens).',
    'reason.max'                 => 'Je reden is te lang (max. 500 tekens).',
]);

        auth()->user()->appeals()->create([
            'reputation_log_id' => $request->reputation_log_id,
            'reason'            => $request->reason,
            'status'            => 'pending',
        ]);

        return back()
            ->with('success', 'Je beroep is ingediend. We laten je binnen 5 werkdagen weten wat de beslissing is.');
    }
}
