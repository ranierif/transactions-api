<?php

namespace App\Exceptions\Authorization;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class UnauthorizedToStoreTransactionException extends Exception
{
    /**
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(
        string $message = 'Unauthorized to store transaction',
        int $code = Response::HTTP_BAD_REQUEST,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
