<?php

namespace App\Exceptions\Notification;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class NotificationToPayeeNotSendedException extends Exception
{
    /**
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(
        string $message = 'Notification to payee not sended',
        int $code = Response::HTTP_BAD_REQUEST,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
