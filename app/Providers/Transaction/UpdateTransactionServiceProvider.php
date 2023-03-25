<?php

namespace App\Providers\Transaction;

use App\Services\Transaction\Contracts\UpdateTransactionServiceContract;
use App\Services\Transaction\UpdateTransactionService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UpdateTransactionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            UpdateTransactionServiceContract::class,
            UpdateTransactionService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            UpdateTransactionServiceContract::class,
        ];
    }
}
