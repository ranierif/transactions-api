<?php

namespace App\Services\Notification\Contracts;

interface NotificationServiceContract
{
    /**
     * @return array
     */
    public function send(): array;
}
