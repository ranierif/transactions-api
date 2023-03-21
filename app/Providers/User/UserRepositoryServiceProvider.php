<?php

namespace App\Providers\User;

use App\Repositories\User\Contracts\UserRepositoryContract;
use App\Repositories\User\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class UserRepositoryServiceProvider extends ServiceProvider
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
