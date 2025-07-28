<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * Exception thrown when an invalid or disallowed state transition is attempted.
 *
 * This exception is typically used in systems that implement state machines or enforce specific workflows where an object's state can only change according to predefined rules.
 * Catching this exception allows for graceful handling of business rule violations related to state changes, preventing the application from entering an inconsistent or invalid state.
 *
 * @package App\Exceptions
 */
final class StateTransitionException extends Exception {}
