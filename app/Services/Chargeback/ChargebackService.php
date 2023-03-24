<?php

namespace App\Services\Chargeback;

use App\Enums\Status;
use App\Exceptions\Chargeback\TransactionAlreadyHasChargebackException;
use App\Models\Chargeback;
use App\Models\Transaction;
use App\Repositories\Chargeback\Contracts\ChargebackRepositoryContract;
use App\Services\Chargeback\Contracts\ChargebackServiceContract;
use App\Services\Transaction\Contracts\GetTransactionServiceContract;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Illuminate\Database\Connection;

class ChargebackService implements ChargebackServiceContract
{
    /**
     * @param  ChargebackRepositoryContract  $chargebackRepository
     * @param  TransactionServiceContract  $transactionService
     * @param  GetTransactionServiceContract  $getTransactionService
     * @param  Connection  $connection
     */
    public function __construct(
        protected ChargebackRepositoryContract $chargebackRepository,
        protected TransactionServiceContract $transactionService,
        protected GetTransactionServiceContract $getTransactionService,
        protected Connection $connection
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function handleChargeback(int $transactionId, ?string $reason): Chargeback
    {
        $transaction = $this->getTransactionService
            ->getTransactionById($transactionId);

        $this->canChargeback($transaction->status_id);

        $chargeback = $this->connection->transaction(function () use ($transaction, $reason) {
            $this->updateOriginTransaction($transaction->id);
            $transactionReversal = $this->storeReversalTransaction($transaction);

            return $this->storeChargeback(
                $transaction,
                $transactionReversal,
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
        return $this->transactionService->storeTransaction(
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
        return $this->transactionService->updateTransactionStatus(
            $transactionId,
            Status::CHARGEBACK->value
        );
    }

    /**
     * @param  Transaction  $origin
     * @param  Transaction  $reversal
     * @param  null|string  $reason
     * @return null|Chargeback
     */
    private function storeChargeback(Transaction $origin, Transaction $reversal, ?string $reason): ?Chargeback
    {
        return $this->chargebackRepository->store([
            'origin_transaction_id' => $origin->id,
            'reversal_transaction_id' => $reversal->id,
            'reason' => $reason,
        ]);
    }
}
