<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use App\Repositories\Base\BaseRepositoryEloquent;
use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;

class TransactionRepositoryEloquent extends BaseRepositoryEloquent implements TransactionRepositoryContract
{
    /**
     * @var Transaction
     */
    protected $model = Transaction::class;
}
