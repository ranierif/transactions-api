<?php

namespace App\Providers\User;

use App\Repositories\User\Contracts\UserRepositoryContract;
use App\Repositories\User\UserRepositoryEloquent;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UserRepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryContract::class,
            UserRepositoryEloquent::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            UserRepositoryContract::class,
        ];
    }
}
