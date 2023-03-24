<?php

namespace App\Services\Transaction;

use App\Enums\DocumentType;
use App\Enums\Status;
use App\Exceptions\Authorization\UnauthorizedToStoreTransactionException;
use App\Exceptions\Notification\NotificationToPayeeNotSendedException;
use App\Exceptions\Transaction\InsufficientFundsToSendException;
use App\Exceptions\Transaction\PayerCannotSendTransactionsException;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;
use App\Services\Authorization\Contracts\AuthorizationServiceContract;
use App\Services\Notification\Contracts\NotificationServiceContract;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use App\Services\User\Contracts\UserServiceContract;
use Illuminate\Support\Collection;

class TransactionService implements TransactionServiceContract
{
    /**
     * @param  TransactionRepositoryContract  $repository
     * @param  UserServiceContract  $userService
     * @param  AuthorizationServiceContract  $authorizationService
     * @param  NotificationServiceContract  $notificationService
     */
    public function __construct(
        protected TransactionRepositoryContract $repository,
        protected UserServiceContract $userService,
        protected AuthorizationServiceContract $authorizationService,
        protected NotificationServiceContract $notificationService
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
    public function handleNewTransaction(int $payerId, int $payeeId, int $value): Transaction
    {
        $payer = $this->userService->findUserById($payerId);

        // 1 - Validate if payer can send transaction
        $this->payerCanSendTransaction($payer);

        // 2 - Validate if payer has balance to send
        $this->payerHasBalanceToSend($payer, $value);

        // 3 - Check authorization
        $this->verifyAuthorization();

        // 4 - Store transaction
        $transaction = $this->storeTransaction(
            $payerId,
            $payeeId,
            $value,
            Status::COMPLETE->value,
        );

        // 5 - Notify payee about new transaction
        $this->sendNotificationToPayee();

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
     * @throws InsufficientFundsToSendException
     */
    private function payerHasBalanceToSend(User $user, int $value): void
    {
        if ($user->balance < $value) {
            throw new InsufficientFundsToSendException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function storeTransaction(int $payerId, int $payeeId, int $value, int $statusId): Transaction
    {
        return $this->repository->store([
            'payer_id' => $payerId,
            'payee_id' => $payeeId,
            'value' => $value,
            'status_id' => $statusId,
        ]);
    }

    /**
     * @return void
     *
     * @throws UnauthorizedToStoreTransactionException
     */
    private function verifyAuthorization(): void
    {
        $authorization = $this->authorizationService->verify();

        if (empty($authorization['success']) || ! $authorization['success']) {
            throw new UnauthorizedToStoreTransactionException();
        }
    }

    /**
     * @return void
     *
     * @throws NotificationToPayeeNotSendedException
     */
    private function sendNotificationToPayee(): void
    {
        $authorization = $this->notificationService->send();

        if (empty($authorization['success']) || ! $authorization['success']) {
            throw new NotificationToPayeeNotSendedException();
        }
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

    /**
     * {@inheritDoc}
     */
    public function updateTransactionStatus(int $transactionId, int $statusId): bool
    {
        return $this->repository->update(
            $transactionId,
            [
                'status_id' => $statusId,
            ]
        );
    }
}
