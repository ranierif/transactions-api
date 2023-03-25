<?php

namespace App\Services\Transaction\Contracts;

interface UpdateTransactionServiceContract
{
    /**
     * @param  mixed  $transactionId
     * @param  mixed  $statusId
     * @return bool
     */
    public function updateStatus(int $transactionId, int $statusId): bool;
}
