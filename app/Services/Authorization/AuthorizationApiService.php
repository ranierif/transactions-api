<?php

namespace App\Services\Authorization;

use App\Services\Authorization\Contracts\AuthorizationApiServiceContract;
use App\Services\Authorization\Contracts\AuthorizationClientContract;
use Psr\Http\Message\ResponseInterface;

class AuthorizationApiService implements AuthorizationApiServiceContract
{
    /**
     * @param  AuthorizationClientContract  $client
     */
    public function __construct(private AuthorizationClientContract $client)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function verify(): ResponseInterface
    {
        return $this->client->request(
            'GET',
            config('services.authorization.token'),
        );
    }
}
