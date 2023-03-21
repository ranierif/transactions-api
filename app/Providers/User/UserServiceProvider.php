<?php

namespace App\Providers\User;

use App\Services\User\Contracts\UserServiceContract;
use App\Services\User\UserService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            UserServiceContract::class,
            UserService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            UserServiceContract::class,
        ];
    }
}
