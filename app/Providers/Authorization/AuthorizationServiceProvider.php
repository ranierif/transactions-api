<?php

namespace App\Providers\Authorization;

use App\Services\Authorization\AuthorizationService;
use App\Services\Authorization\Contracts\AuthorizationServiceContract;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            AuthorizationServiceContract::class,
            AuthorizationService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            AuthorizationServiceContract::class,
        ];
    }
}
