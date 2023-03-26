<?php

namespace App\Services\Chargeback;

use App\Enums\Status;
use App\Exceptions\Chargeback\TransactionAlreadyHasChargebackException;
use App\Models\Chargeback;
use App\Models\Transaction;
use App\Repositories\Chargeback\Contracts\ChargebackRepositoryContract;
use App\Services\Chargeback\Contracts\ChargebackServiceContract;
use App\Services\Chargeback\Contracts\StoreChargebackServiceContract;
use App\Services\Transaction\Contracts\GetTransactionServiceContract;
use App\Services\Transaction\Contracts\StoreTransactionServiceContract;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use App\Services\Transaction\Contracts\UpdateTransactionServiceContract;
use Illuminate\Database\Connection;

class ChargebackService implements ChargebackServiceContract
{
    /**
     * @param  ChargebackRepositoryContract  $chargebackRepository
     * @param  TransactionServiceContract  $transactionService
     * @param  GetTransactionServiceContract  $getTransaction
     * @param  StoreTransactionServiceContract  $storeTransaction
     * @param  UpdateTransactionServiceContract  $updateTransaction
     * @param  StoreChargebackServiceContract  $storeChargebackService
     * @param  Connection  $connection
     */
    public function __construct(
        protected ChargebackRepositoryContract $chargebackRepository,
        protected TransactionServiceContract $transactionService,
        protected GetTransactionServiceContract $getTransaction,
        protected StoreTransactionServiceContract $storeTransaction,
        protected UpdateTransactionServiceContract $updateTransaction,
        protected StoreChargebackServiceContract $storeChargebackService,
        protected Connection $connection
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function handleChargeback(int $transactionId, ?string $reason): Chargeback
    {
        $transaction = $this->getTransaction
            ->getTransactionById($transactionId);

        $this->canChargeback($transaction->status_id);

        $chargeback = $this->connection->transaction(function () use ($transaction, $reason) {
            $this->updateOriginTransaction($transaction->id);
            $transactionReversal = $this->storeReversalTransaction($transaction);

            return $this->storeChargebackService
                    ->storeChargeback(
                        $transaction->id,
                        $transactionReversal->id,
                        $reason
                    );
        });

        return $chargeback;
    }

    /**
     * @param  int  $transactionStatusId
     * @return void
     *
     * @throws TransactionAlreadyHasChargebackException
     */
    private function canChargeback(int $transactionStatusId): void
    {
        if ($transactionStatusId == Status::CHARGEBACK->value) {
            throw new TransactionAlreadyHasChargebackException();
        }
    }

    /**
     * @param  mixed  $transaction
     * @return Transaction
     */
    private function storeReversalTransaction(Transaction $transaction): Transaction
    {
        return $this->storeTransaction->storeTransaction(
            payerId: $transaction->payee_id,
            payeeId: $transaction->payer_id,
            value: $transaction->value,
            statusId: Status::COMPLETE->value
        );
    }

    /**
     * @param  mixed  $transactionId
     * @return bool
     */
    private function updateOriginTransaction(int $transactionId): bool
    {
        return $this->updateTransaction->updateStatus(
            $transactionId,
            Status::CHARGEBACK->value
        );
    }
}
