<?php

namespace App\Providers\Transaction;

use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;
use App\Repositories\Transaction\TransactionRepositoryEloquent;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TransactionRepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            TransactionRepositoryContract::class,
            TransactionRepositoryEloquent::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            TransactionRepositoryContract::class,
        ];
    }
}
