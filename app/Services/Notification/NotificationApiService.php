<?php

namespace App\Services\Notification;

use App\Services\Notification\Contracts\NotificationApiServiceContract;
use App\Services\Notification\Contracts\NotificationClientContract;
use Psr\Http\Message\ResponseInterface;

class NotificationApiService implements NotificationApiServiceContract
{
    /**
     * @param  NotificationClientContract  $client
     */
    public function __construct(private NotificationClientContract $client)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function send(): ResponseInterface
    {
        return $this->client->request(
            'GET',
            'notify',
        );
    }
}
