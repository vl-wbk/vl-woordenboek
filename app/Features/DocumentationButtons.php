<?php

declare(strict_types=1);

namespace App\Features;

/**
 * Represents a feature toggle or a simple flag to control the visibility or activation of documentation-related buttons or functionalities within the app.
 *
 * This class is designed as a `final readonly` class, indicating that its state can't be changed after instantiation, and it can't be extended.
 * Its primary purpose is to provide a simple mechanism to resolve whether documentation buttons should be displayed or enabled.
 *
 * This pattern is often used in feature flagging systems where certain UI elements or app behaviors can be dynamically turned on or off.
 *
 * @package App\Features
 */
final readonly class DocumentationButtons
{
    /**
     * Resolves the state of the documentation buttons feature.
     *
     * This method determines whether the documentation buttons should be active.
     * In its current implementation, it always returns `true`, meaning the documentation buttons are always enabled.
     * This can be extended in the future to include more complex logic, such as checking environment variables, database configurations, or user permissions.
     */
    public function resolve(): true
    {
        return true;
    }
}
