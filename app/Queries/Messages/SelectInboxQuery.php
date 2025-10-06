<?php

declare(strict_types=1);

namespace App\Queries\Messages;

use App\Enums\Inbox;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final readonly class SelectInboxQuery
{
    /**
     * @return Builder<Thread>
     */
    public function compose(Request $request): Builder
    {
        if ($request->has('onderwerp')) {
            return Thread::forUser(auth()->id())->where('subject', 'like', "%" . $request->get('onderwerp'));
        }

        return match ($request->get('type')) {
            Inbox::All->value => Thread::forUser(auth()->id()),
            default => Thread::forUserWithNewMessages(auth()->id()),
        };
    }
}
