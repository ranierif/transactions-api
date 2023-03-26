<?php

namespace App\Services\Transaction\Contracts;

use App\Models\Transaction;

interface StoreTransactionServiceContract
{
    /**
     * @param  int  $payerId
     * @param  int  $payeeId
     * @param  int  $value
     * @param  int  $statusId
     * @return null|Transaction
     */
    public function storeTransaction(int $payerId, int $payeeId, int $value, int $statusId): ?Transaction;
}
