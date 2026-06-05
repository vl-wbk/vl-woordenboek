<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Exceptions\DatabaseTransactionException;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Provides a standardized wrapper for database transactions.
 *
 * This trait ensures that any database operations wrapped via `executeInTransaction` are executed atomically.
 * If a failure occurs, the exception is caught, translated into a domain-specific `DatabaseTransactionException`, and
 * re-thrown, ensuring consistent error handling and reporting across our services.
 */
trait HandlesDatabaseTransactions
{
    /**
     * Executes the given callback within a database transaction.
     *
     * If the database transaction fails, he caught exception is caught and re-thrown as
     * a 'DatabaseTransactionException' to trigger our standard reporting and recovery flow.
     *
     * @template TReturn
     *
     * @param  callable(): TReturn $callback The database logic to excecute
     * @return TReturn
     *
     * @throws Throwable
     */
    protected function executeInTransaction(callable $callback): mixed
    {
        return rescue(
            callback: fn () => DB::transaction($callback),
            rescue: fn (Throwable $exception) => throw DatabaseTransactionException::dueToFailure($exception),
        );
    }
}
