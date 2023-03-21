<?php

namespace App\Repositories\User\Contracts;

use App\Repositories\Base\Contracts\BaseRepositoryContract;
use Illuminate\Support\Collection;

interface UserRepositoryContract extends BaseRepositoryContract
{
    /**
     * @param  null|array  $filters
     * @return Collection
     */
    public function getUsers(?array $filters): Collection;
}
