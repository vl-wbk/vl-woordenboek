<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
class Todo
{
    public function __construct(
        public string $message,
        public ?string $priority = null, // e.g., 'High', 'Medium', 'Low'
        public ?string $assignee = null, // e.g., 'John Doe', 'Team Alpha'
        public ?string $dueDate = null   // e.g., '2025-08-30'
    ) {}
}
