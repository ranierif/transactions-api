<?php

namespace App\Providers\Authorization;

use App\Services\Authorization\AuthorizationClient;
use App\Services\Authorization\Contracts\AuthorizationClientContract;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AuthorizationClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            AuthorizationClientContract::class,
            fn () => new AuthorizationClient([
                'base_uri' => config('services.authorization.url'),
                RequestOptions::TIMEOUT => config('services.authorization.timeout'),
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
        return [AuthorizationClientContract::class];
    }
}
