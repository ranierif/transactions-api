<?php

namespace App\Providers\Notification;

use App\Services\Notification\Contracts\NotificationClientContract;
use App\Services\Notification\NotificationClient;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class NotificationClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            NotificationClientContract::class,
            fn () => new NotificationClient([
                'base_uri' => config('services.notification.url'),
                RequestOptions::TIMEOUT => config('services.notification.timeout'),
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ])
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [NotificationClientContract::class];
    }
}
