<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Auth\Access\Response;

final readonly class ThreadPolicy
{
    public function view(User $user, Thread $thread): Response
	{
		if ($thread->hasParticipant($user->id)) {
			return Response::allow();
		}
		
		return Response::denyAsNotFound();
	}
	
	public function reply(User $user, Thread $thread): Response
	{
		if ($thread->hasParticipant($user->id)) {
			return Response::allow();
		}
		
		return Response::denyAsNotFound();
	}
	
	public function removeParticipants(User $user, Thread $thread, Participant $participant): Response
	{
		if ($thread->creator()->is($user) && $thread->hasParticipant($user->id) && $participant->user()->isNot($thread->creator())) {
			return Response::allow();
		}
		
		return Response::deny();
	}
	
	public function addParticipants(User $user, Thread $thread): Response
	{
		if ($thread->creator()->is($user) && $thread->participants->count() < 5) {
			return Response::allow();
		}
		
		return Response::deny();
	}
	
	public function leave(User $user, Thread $thread): Response
	{
		if ($thread->hasParticipant($user->id) && $thread->creator()->isNot($user)) {
			return Response::allow();
		}
		
		return Response::deny();
	}
}
