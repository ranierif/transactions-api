<?php

namespace App\Services\Transaction\Contracts;

use App\Models\Transaction;

interface TransactionServiceContract
{
    /**
     * @param  int  $payerId
     * @param  int  $payeeId
     * @param  int  $value
     * @return Transaction
     */
    public function handleNewTransaction(int $payerId, int $payeeId, int $value): Transaction;
}
