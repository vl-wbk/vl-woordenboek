<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Messages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\StoreReplyRequest;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;

#[Middleware(middleware: ['auth', 'verified', 'forbid-banned-user'])]
final readonly class MessageController
{
	#[Post(uri: '/inbox/thread/{thread}/antwoord', name: 'thread:reply')]
    public function store(StoreReplyRequest $storeReplyRequest, Thread $thread): RedirectResponse
	{
		$thread->messages()->create(attributes: ['user_id' => $storeReplyRequest->user()->id, 'body' => $storeReplyRequest->getData()->message]);
		
		return back();
	}
}
