<?php

	declare(strict_types=1);

	namespace App\Actions\Messages;

	use App\Data\MessageObjectData;
	use App\Models\User;
	use Cmgmyr\Messenger\Models\Message;
	use Cmgmyr\Messenger\Models\Participant;
	use Cmgmyr\Messenger\Models\Thread;
	use Illuminate\Support\Facades\DB;
    use Throwable;

	final readonly class SendMessage
	{
        /**
         * Handles the process of sending a new message.
         *
         * @throws Throwable when the transaction couldn't be successfully executed.
         */
		public function handle(MessageObjectData $messageObjectData): Thread
		{
			return DB::transaction(function () use ($messageObjectData): Thread {
				$thread = $this->createThread($messageObjectData);
				$this->addParticipantsToThread($thread, $messageObjectData);
				$this->createMessage($thread, $messageObjectData);

				return $thread;
			});
		}

		/**
		 * Creates a new message thread.
		 */
		private function createThread(MessageObjectData $messageObjectData): Thread
		{
			return Thread::query()->create([
				'subject' => $messageObjectData->getSubject(),
			]);
		}

		/**
		 * Adds the sender and receiver as participants to the thread.
		 */
		private function addParticipantsToThread(Thread $thread, MessageObjectData $messageObjectData): void
		{
			$receiver = User::where('name', $messageObjectData->getReceiver())->firstOrFail();

			$thread->addParticipant($receiver->id);
			$thread->addParticipant(auth()->id());
		}

		/**
		 * Creates and attaches the message to the thread.
		 */
		private function createMessage(Thread $thread, MessageObjectData $messageObjectData): void
		{
			Message::query()->create([
				'thread_id' => $thread->id,
				'user_id' => auth()->id(),
				'body' => $messageObjectData->getMessage(),
			]);
		}
	}
