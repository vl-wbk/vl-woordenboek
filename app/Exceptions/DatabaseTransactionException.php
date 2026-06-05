<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;
use Sentry\Severity;
use Sentry\State\Scope;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

use function Sentry\captureException;
use function Sentry\configureScope;

/**
 * Exception thrown when a database transaction fails to complete.
 *
 * This Exception class handles the reporting and user-facing response for transaction errors.
 * It automatically sends detailed debugging content-inclusing sanitized request input
 * and the underlying error message to Sentry. It then provides a user-friendly feedback message via
 * a flash notification, allowing the user to retry their request without losing their input.
 */
final class DatabaseTransactionException extends Exception
{

    /**
     * Creates a new instance for a failed database transaction.
     *
     * @param  Throwable $previous The underlying error that caused the failure.
     */
    public static function dueToFailure(Throwable $previous): self
    {
        return new self('Database transactie mislukt: '.$previous->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }

    /**
     * Reports the exception to Sentry with enriched request context.
     *
     * We attach the relevant request parameters (excluding sensitive fields) and the previous exception message
     * to the Sentry scope to make debugging faster and more accurate.
     */
    public function report(): void
    {
        configureScope(function (Scope $scope): void {
            $scope->setTag('exception_type', 'database_transaction');
            $scope->setLevel(Severity::error());

            $scope->setContext('details', value: [
                'message' => $this->getMessage(),
                'previous' => $this->getPrevious()?->getMessage(),
            ]);

            /** @var array<string, mixed> $requestInput */
            $requestInput = request()->except(['password', 'password_confirmation']);

            $scope->setContext('request_input', $requestInput);
        });

        captureException($this);
    }

    /**
     * Renders a user-friendly error response.
     *
     * To prevent masking underlying issues during local development, this method yields to the framework's default
     * exception handler when debug mode is enabled. In production, it provides a graceful fallback by flashing an
     * error message to the user and redirecting them back to the previous request with their input preserved.
     *
     * @return RedirectResponse|bool Returns a redirect response on failure, or false to bypass rendering during development.
     */
    public function render(): RedirectResponse|bool
    {
        if (config()->boolean('app.debug')) {
            return false;
        }

        flash(text: 'Er is een technische fout opgetreden bij het verwerken van uw verzoek.', class: 'text-danger');

        return back()->withInput();
    }
}
