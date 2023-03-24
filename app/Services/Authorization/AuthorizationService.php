<?php

namespace App\Services\Authorization;

use App\Services\Authorization\Contracts\AuthorizationApiServiceContract;
use App\Services\Authorization\Contracts\AuthorizationServiceContract;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;
use Psr\Http\Message\ResponseInterface;

class AuthorizationService implements AuthorizationServiceContract
{
    /**
     * @param  AuthorizationApiServiceContract  $service
     * @param  Logger  $logger
     */
    public function __construct(
        private AuthorizationApiServiceContract $service,
        private Logger $logger
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function verify(): array
    {
        try {
            $response = $this->service->verify();

            return $this->formatResponse($response);
        } catch (RequestException $exception) {
            return $this->formatExceptionResponse($exception->getResponse());
        } catch (ConnectException) {
            return $this->formatExceptionResponse($this->createConnectionExceptionResponse());
        }
    }

    /**
     * @param  ResponseInterface  $response
     * @return array
     */
    private function formatResponse(ResponseInterface $response): array
    {
        $status = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);
        $responseData = ['message' => $data['message'] ?? $data];
        $this->logResponse($status, $data);

        switch ($status) {
            case Response::HTTP_OK:
                $responseData['success'] = true;
                break;
            default:
                $responseData['success'] = false;
                break;
        }

        return $responseData;
    }

    /**
     * @param  ResponseInterface  $response
     * @return array
     */
    private function formatExceptionResponse(ResponseInterface $response): array
    {
        $status = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);
        $this->logResponse($status, $data);

        return [
            'success' => false,
            'message' => $data['message'] ?? 'No Authorization service response',
        ];
    }

    /**
     * @return ResponseInterface
     */
    private function createConnectionExceptionResponse(): ResponseInterface
    {
        return new Psr7Response(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            [],
            json_encode(['message' => 'Couldn\'t connect to Authorization service'])
        );
    }

    /**
     * @param  int  $status
     * @param  null|array  $data
     * @return void
     */
    private function logResponse(int $status, ?array $data): void
    {
        $this->logger->debug('Log Authorization Response', [
            'status' => $status,
            'data' => $data,
        ]);
    }
}
