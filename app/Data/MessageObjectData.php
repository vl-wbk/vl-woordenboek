<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class MessageObjectData extends Data
{
    public function __construct(
		#[MapInputName('onderwerp')]
        public readonly string $subject,
		#[MapInputName('ontvanger')]
		public readonly string $receiver,
		#[MapInputName('bericht')]
		public readonly string $message,
    ) {}
	
	public function getSubject(): string
	{
		return $this->subject;
	}
	
	public function getReceiver(): string
	{
		return $this->receiver;
	}
	
	public function getMessage(): string
	{
		return $this->message;
	}
}
