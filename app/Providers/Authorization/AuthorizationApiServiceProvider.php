<?php

namespace App\Providers\Authorization;

use App\Services\Authorization\AuthorizationApiService;
use App\Services\Authorization\Contracts\AuthorizationApiServiceContract;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AuthorizationApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            AuthorizationApiServiceContract::class,
            AuthorizationApiService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            AuthorizationApiServiceContract::class,
        ];
    }
}
