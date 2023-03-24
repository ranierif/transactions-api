<?php

namespace App\Exceptions\Transaction;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class InsufficientFundsToSendException extends Exception
{
    /**
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(
        string $message = 'Insufficient fund to send transaction',
        int $code = Response::HTTP_BAD_REQUEST,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
