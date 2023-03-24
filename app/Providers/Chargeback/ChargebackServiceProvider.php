<?php

namespace App\Providers\Chargeback;

use App\Services\Chargeback\ChargebackService;
use App\Services\Chargeback\Contracts\ChargebackServiceContract;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ChargebackServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            ChargebackServiceContract::class,
            ChargebackService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            ChargebackServiceContract::class,
        ];
    }
}
