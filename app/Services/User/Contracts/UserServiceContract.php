<?php

namespace App\Services\User\Contracts;

use Illuminate\Support\Collection;

interface UserServiceContract
{
    /**
     * @param  null|array  $filters
     * @return Collection
     */
    public function getUsers(?array $filters): Collection;
}
