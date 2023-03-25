<?php

namespace App\Services\Transaction\Contracts;

interface ValidatePayerServiceContract
{
    /**
     * @param  int  $userId
     * @return void
     *
     * @throws PayerCannotSendTransactionsException
     */
    public function canSendTransaction(int $userId): void;

    /**
     * @param  int  $userId
     * @param  int  $value
     * @return void
     *
     * @throws InsufficientFundsToSendException
     */
    public function hasBalanceToSend(int $userId, int $value): void;
}
