<?php

namespace App\Services\Authorization\Contracts;

interface AuthorizationServiceContract
{
    /**
     * @return array
     */
    public function verify(): array;
}
