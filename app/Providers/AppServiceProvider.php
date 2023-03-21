<?php

namespace App\Providers;

use App\Repositories\Base\BaseRepositoryEloquent;
use App\Repositories\Base\Contracts\BaseRepositoryContract;
use App\Repositories\User\Contracts\UserRepositoryContract;
use App\Repositories\User\UserRepositoryEloquent;
use App\Services\User\Contracts\UserServiceContract;
use App\Services\User\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
