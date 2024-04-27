<?php

namespace App\Exception;

use Exception;
use Throwable;

/**
 * Class DatabaseOperationException
 *
 * This class represents an exception that is thrown when a database operation fails.
 */
class DatabaseOperationException extends Exception
{
    /**
     * DatabaseOperationException constructor.
     *
     * @param string $message The Exception message to throw.
     * @param int $code The Exception code.
     * @param Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}