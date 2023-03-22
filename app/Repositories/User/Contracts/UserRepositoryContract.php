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

    /**
     * @param  int  $userId
     * @param  int  $value
     * @return bool
     */
    public function removeBalance(int $userId, int $value): bool;

    /**
     * @param  int  $userId
     * @param  int  $value
     * @return bool
     */
    public function addBalance(int $userId, int $value): bool;
}
