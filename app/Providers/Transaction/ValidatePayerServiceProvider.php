<?php

namespace App\Providers\Transaction;

use App\Services\Transaction\Contracts\ValidatePayerServiceContract;
use App\Services\Transaction\ValidatePayerService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ValidatePayerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            ValidatePayerServiceContract::class,
            ValidatePayerService::class
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            ValidatePayerServiceContract::class,
        ];
    }
}
