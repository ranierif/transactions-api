<?php

namespace App\Providers\Chargeback;

use App\Repositories\Chargeback\ChargebackRepositoryEloquent;
use App\Repositories\Chargeback\Contracts\ChargebackRepositoryContract;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ChargebackRepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            ChargebackRepositoryContract::class,
            ChargebackRepositoryEloquent::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            ChargebackRepositoryContract::class,
        ];
    }
}
