<?php

namespace App\Providers\Transaction;

use App\Services\Transaction\Contracts\TransactionServiceContract;
use App\Services\Transaction\TransactionService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            TransactionServiceContract::class,
            TransactionService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            TransactionServiceContract::class,
        ];
    }
}
