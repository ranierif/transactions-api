<?php

namespace App\Providers\Transaction;

use App\Services\Transaction\Contracts\GetTransactionServiceContract;
use App\Services\Transaction\GetTransactionService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class GetTransactionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            GetTransactionServiceContract::class,
            GetTransactionService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            GetTransactionServiceContract::class,
        ];
    }
}
