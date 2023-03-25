<?php

namespace App\Services\Transaction;

use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;
use App\Services\Transaction\Contracts\UpdateTransactionServiceContract;

class UpdateTransactionService implements UpdateTransactionServiceContract
{
    /**
     * @param  TransactionRepositoryContract  $repository
     */
    public function __construct(
        protected TransactionRepositoryContract $repository,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function updateStatus(int $transactionId, int $statusId): bool
    {
        return $this->repository->update(
            $transactionId,
            [
                'status_id' => $statusId,
            ]
        );
    }
}
