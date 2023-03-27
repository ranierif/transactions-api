<?php

namespace App\Services\Authorization;

use App\Services\Authorization\Contracts\AuthorizationClientContract;
use GuzzleHttp\Client;

class AuthorizationClient extends Client implements AuthorizationClientContract
{
    /**
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }
}
