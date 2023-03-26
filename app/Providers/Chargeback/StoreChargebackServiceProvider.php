<?php

namespace App\Providers\Chargeback;

use App\Services\Chargeback\Contracts\StoreChargebackServiceContract;
use App\Services\Chargeback\StoreChargebackService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class StoreChargebackServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            StoreChargebackServiceContract::class,
            StoreChargebackService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            StoreChargebackServiceContract::class,
        ];
    }
}
