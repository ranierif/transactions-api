<?php

namespace App\Services\Transaction;

use App\Enums\DocumentType;
use App\Exceptions\Transaction\InsufficientFundsToSendException;
use App\Exceptions\Transaction\PayerCannotSendTransactionsException;
use App\Services\Transaction\Contracts\ValidatePayerServiceContract;
use App\Services\User\Contracts\UserServiceContract;

class ValidatePayerService implements ValidatePayerServiceContract
{
    /**
     * @param  UserServiceContract  $userService
     */
    public function __construct(
        protected UserServiceContract $userService,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function canSendTransaction(int $userId): void
    {
        $payer = $this->userService->findUserById($userId);

        if ($payer->document_type_id != DocumentType::CPF->value) {
            throw new PayerCannotSendTransactionsException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function hasBalanceToSend(int $userId, int $value): void
    {
        $payer = $this->userService->findUserById($userId);

        if ($payer->balance < $value) {
            throw new InsufficientFundsToSendException();
        }
    }
}
