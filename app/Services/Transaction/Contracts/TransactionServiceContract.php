<?php

namespace App\Services\Transaction\Contracts;

use Illuminate\Support\Collection;

interface TransactionServiceContract
{
    /**
     * @param  int  $userId
     * @return Collection
     */
    public function getTransactionsByUserId(int $userId): Collection;
}
