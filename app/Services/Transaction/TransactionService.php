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
use App\Services\Transaction\Contracts\GetTransactionServiceContract;
use App\Services\Transaction\Contracts\StoreTransactionServiceContract;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use App\Services\User\Contracts\UserServiceContract;

class TransactionService implements TransactionServiceContract
{
    /**
     * @param  TransactionRepositoryContract  $repository
     * @param  UserServiceContract  $userService
     * @param  AuthorizationServiceContract  $authorizationService
     * @param  NotificationServiceContract  $notificationService
     * @param  GetTransactionServiceContract  $getTransactionService
     * @param  StoreTransactionServiceContract  $storeTransactionService
     */
    public function __construct(
        protected TransactionRepositoryContract $repository,
        protected UserServiceContract $userService,
        protected AuthorizationServiceContract $authorizationService,
        protected NotificationServiceContract $notificationService,
        protected GetTransactionServiceContract $getTransactionService,
        protected StoreTransactionServiceContract $storeTransactionService
    ) {
        //
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
        $transaction = $this->storeTransactionService
            ->storeTransaction(
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
