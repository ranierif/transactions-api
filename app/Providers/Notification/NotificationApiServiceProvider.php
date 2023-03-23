<?php

namespace App\Providers\Notification;

use App\Services\Notification\NotificationApiService;
use App\Services\Notification\Contracts\NotificationApiServiceContract;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class NotificationApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            NotificationApiServiceContract::class,
            NotificationApiService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            NotificationApiServiceContract::class,
        ];
    }
}
