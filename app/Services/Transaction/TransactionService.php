<?php

namespace App\Services\Transaction;

use App\Enums\DocumentType;
use App\Enums\Status;
use App\Exceptions\Transaction\InsufficientFundsToSendTransactionException;
use App\Exceptions\Transaction\PayerCannotSendTransactionsException;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use App\Services\User\Contracts\UserServiceContract;
use Illuminate\Support\Collection;

class TransactionService implements TransactionServiceContract
{
    /**
     * @param  TransactionRepositoryContract  $transactionRepository
     * @param  UserServiceContract  $userService
     */
    public function __construct(
        protected TransactionRepositoryContract $transactionRepository,
        protected UserServiceContract $userService
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

    public function handleNewTransaction(int $payerId, int $payeeId, int $value): Transaction
    {
        $payer = $this->userService->findUserById($payerId);

        // 1 - Validate if payer can send transaction
        $this->payerCanSendTransaction($payer);

        // 2 - Validate if payer has balance to send
        $this->payerHasBalanceToSend($payer, $value);

        // 3 - Check authorization

        // 4 - Store transaction
        $transaction = $this->storeTransaction(
            $payerId,
            $payeeId,
            $value,
            Status::COMPLETE->value,
        );

        // 4 - Notify payee about new transaction

        return $transaction;
    }

    /**
     * @param  User  $user
     * @return void
     *
     * @throws PayerCannotSendTransactionsException
     */
    private function payerCanSendTransaction(User $user): void
    {
        if ($user->document_type_id != DocumentType::CPF->value) {
            throw new PayerCannotSendTransactionsException();
        }
    }

    /**
     * @param  User  $user
     * @param  int  $value
     * @return void
     *
     * @throws InsufficientFundsToSendTransactionException
     */
    private function payerHasBalanceToSend(User $user, int $value): void
    {
        if ($user->balance < $value) {
            throw new InsufficientFundsToSendTransactionException();
        }
    }

    /**
     * @param  int  $payerId
     * @param  int  $payeeId
     * @param  int  $value
     * @param  int  $statusId
     * @return Transaction
     */
    private function storeTransaction(int $payerId, int $payeeId, int $value, int $statusId): Transaction
    {
        return $this->transactionRepository->store([
            'payer_id' => $payerId,
            'payee_id' => $payeeId,
            'value' => $value,
            'status_id' => $statusId,
        ]);
    }
}
