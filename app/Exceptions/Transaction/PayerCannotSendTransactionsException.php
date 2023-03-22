<?php

namespace App\Exceptions\Transaction;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class PayerCannotSendTransactionsException extends Exception
{
    /**
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(
        string $message = 'The payer selected cannot send transactions',
        int $code = Response::HTTP_BAD_REQUEST,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
