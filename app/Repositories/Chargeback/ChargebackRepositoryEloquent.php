<?php

namespace App\Repositories\Chargeback;

use App\Models\Chargeback;
use App\Repositories\Base\BaseRepositoryEloquent;
use App\Repositories\Chargeback\Contracts\ChargebackRepositoryContract;

class ChargebackRepositoryEloquent extends BaseRepositoryEloquent implements ChargebackRepositoryContract
{
    /**
     * @var Chargeback
     */
    protected $model = Chargeback::class;
}
