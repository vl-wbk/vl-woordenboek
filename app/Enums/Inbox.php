<?php

declare(strict_types=1);

namespace App\Enums;

enum Inbox: string
{
	case All = 'alle';
	case Unread = 'ongelezen';
}