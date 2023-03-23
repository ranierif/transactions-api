<?php

namespace App\Services\Notification;

use App\Services\Notification\Contracts\NotificationClientContract;
use GuzzleHttp\Client;

class NotificationClient extends Client implements NotificationClientContract
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }
}
