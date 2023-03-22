<?php

namespace App\Services\Transaction\Contracts;

use App\Models\Transaction;
use Illuminate\Support\Collection;

interface TransactionServiceContract
{
    /**
     * @param  int  $userId
     * @return Collection
     */
    public function getTransactionsByUserId(int $userId): Collection;

    /**
     * @param  int  $payerId
     * @param  int  $payeeId
     * @param  int  $value
     * @return Transaction
     */
    public function handleNewTransaction(int $payerId, int $payeeId, int $value): Transaction;
}
