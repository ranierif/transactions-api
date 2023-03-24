<?php

namespace App\Providers\Transaction;

use App\Services\Transaction\Contracts\StoreTransactionServiceContract;
use App\Services\Transaction\StoreTransactionService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class StoreTransactionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            StoreTransactionServiceContract::class,
            StoreTransactionService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            StoreTransactionServiceContract::class,
        ];
    }
}
