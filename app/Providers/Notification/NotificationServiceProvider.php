<?php

namespace App\Providers\Notification;

use App\Services\Notification\Contracts\NotificationServiceContract;
use App\Services\Notification\NotificationService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            NotificationServiceContract::class,
            NotificationService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            NotificationServiceContract::class,
        ];
    }
}
