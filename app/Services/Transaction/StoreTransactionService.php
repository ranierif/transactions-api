<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;
use App\Services\Transaction\Contracts\StoreTransactionServiceContract;

class StoreTransactionService implements StoreTransactionServiceContract
{
    /**
     * @param  TransactionRepositoryContract  $repository
     */
    public function __construct(
        protected TransactionRepositoryContract $repository
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function storeTransaction(int $payerId, int $payeeId, int $value, int $statusId): ?Transaction
    {
        return $this->repository->store([
            'payer_id' => $payerId,
            'payee_id' => $payeeId,
            'value' => $value,
            'status_id' => $statusId,
        ]);
    }
}
