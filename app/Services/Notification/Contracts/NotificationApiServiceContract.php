<?php

namespace App\Services\Notification\Contracts;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

interface NotificationApiServiceContract
{
    /**
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function send(): ResponseInterface;
}
