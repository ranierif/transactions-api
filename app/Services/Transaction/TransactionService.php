<?php

namespace App\Services\Transaction;

use App\Enums\Status;
use App\Exceptions\Authorization\UnauthorizedToStoreTransactionException;
use App\Jobs\Notification\SendNotificationJob;
use App\Models\Transaction;
use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;
use App\Services\Authorization\Contracts\AuthorizationServiceContract;
use App\Services\Transaction\Contracts\StoreTransactionServiceContract;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use App\Services\Transaction\Contracts\ValidatePayerServiceContract;
use App\Services\User\Contracts\UserServiceContract;

class TransactionService implements TransactionServiceContract
{
    /**
     * @param  TransactionRepositoryContract  $repository
     * @param  UserServiceContract  $userService
     * @param  AuthorizationServiceContract  $authorizationService
     * @param  StoreTransactionServiceContract  $storeService
     * @param  ValidatePayerServiceContract  $validatePayerService
     */
    public function __construct(
        protected TransactionRepositoryContract $repository,
        protected UserServiceContract $userService,
        protected AuthorizationServiceContract $authorizationService,
        protected StoreTransactionServiceContract $storeService,
        protected ValidatePayerServiceContract $validatePayerService
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function handleNewTransaction(int $payerId, int $payeeId, int $value): Transaction
    {
        // 1 - Validate if payer can send transaction
        $this->validatePayerService
            ->canSendTransaction($payerId);

        // 2 - Validate if payer has balance to send
        $this->validatePayerService
            ->hasBalanceToSend($payerId, $value);

        // 3 - Check authorization
        $this->verifyAuthorization();

        // 4 - Store transaction
        $transaction = $this->storeService
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
     */
    private function sendNotificationToPayee(): void
    {
        $job = new SendNotificationJob();
        $job->dispatch();
    }
}
