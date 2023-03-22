<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Services\User\Contracts\UserServiceContract;

class TransactionObserver
{
    /**
     * @param  UserServiceContract  $userService
     */
    public function __construct(private UserServiceContract $userService)
    {
        //
    }

    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $this->userService
            ->removeBalance($transaction->payer_id, $transaction->value);

        $this->userService
            ->addBalance($transaction->payee_id, $transaction->value);
    }
}
