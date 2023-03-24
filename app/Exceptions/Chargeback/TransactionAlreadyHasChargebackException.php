<?php

namespace App\Exceptions\Chargeback;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class TransactionAlreadyHasChargebackException extends Exception
{
    /**
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(
        string $message = 'The transaction already has chargeback',
        int $code = Response::HTTP_BAD_REQUEST,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
