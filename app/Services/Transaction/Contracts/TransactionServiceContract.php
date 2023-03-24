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

    /**
     * @param  int  $payerId
     * @param  int  $payeeId
     * @param  int  $value
     * @param  int  $statusId
     * @return Transaction
     */
    public function storeTransaction(int $payerId, int $payeeId, int $value, int $statusId): Transaction;

    /**
     * @param  int  $transactionId
     * @return null|Transaction
     */
    public function getTransactionById(int $transactionId): ?Transaction;

    /**
     * @param  null|array  $filters
     * @return Collection
     */
    public function getTransactions(?array $filters): Collection;

    /**
     * @param  mixed  $transactionId
     * @param  mixed  $statusId
     * @return bool
     */
    public function updateTransactionStatus(int $transactionId, int $statusId): bool;
}
