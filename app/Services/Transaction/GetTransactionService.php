<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;
use App\Services\Transaction\Contracts\GetTransactionServiceContract;
use Illuminate\Support\Collection;

class GetTransactionService implements GetTransactionServiceContract
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
    public function getTransactionsByUserId(int $userId): Collection
    {
        return $this->repository->getTransactionsByUserId($userId);
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactionById(int $transactionId): ?Transaction
    {
        return $this->repository->findBy('id', $transactionId);
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactions(?array $filters): Collection
    {
        return $this->repository->getTransactions($filters);
    }
}
