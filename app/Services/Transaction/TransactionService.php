<?php

namespace App\Services\Transaction;

use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Illuminate\Support\Collection;

class TransactionService implements TransactionServiceContract
{
    /**
     * @param  TransactionRepositoryContract  $transactionRepository
     */
    public function __construct(
        protected TransactionRepositoryContract $transactionRepository
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactionsByUserId(int $userId): Collection
    {
        return $this->transactionRepository->getTransactionsByUserId($userId);
    }
}
