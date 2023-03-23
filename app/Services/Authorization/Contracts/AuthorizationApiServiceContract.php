<?php

namespace App\Services\Authorization\Contracts;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

interface AuthorizationApiServiceContract
{
    /**
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function verify(): ResponseInterface;
}
