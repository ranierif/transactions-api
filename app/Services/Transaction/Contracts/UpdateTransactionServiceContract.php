<?php

namespace App\Services\Transaction\Contracts;

interface UpdateTransactionServiceContract
{
    /**
     * @param  int  $transactionId
     * @param  int  $statusId
     * @return bool
     */
    public function updateStatus(int $transactionId, int $statusId): bool;
}
