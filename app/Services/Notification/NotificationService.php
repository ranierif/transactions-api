<?php

namespace App\Services\Notification;

use App\Services\Notification\Contracts\NotificationApiServiceContract;
use App\Services\Notification\Contracts\NotificationServiceContract;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class NotificationService implements NotificationServiceContract
{
    /**
     * @param  NotificationApiServiceContract  $service
     */
    public function __construct(private NotificationApiServiceContract $service)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function send(): array
    {
        try {
            $response = $this->service->send();

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
            'message' => $data['message'] ?? 'No Notification service response',
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
            json_encode(['message' => 'Couldn\'t connect to Notification service'])
        );
    }

    /**
     * @param  int  $status
     * @param  null|array  $data
     * @return void
     */
    private function logResponse(int $status, ?array $data): void
    {
        Log::debug('Log Notification Response', [
            'status' => $status,
            'data' => $data,
        ]);
    }
}
