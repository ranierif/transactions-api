<?php

namespace App\Http\Controllers\Chargeback;

use App\Exceptions\Chargeback\TransactionAlreadyHasChargebackException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chargeback\ChargebackStoreRequest;
use App\Http\Resources\Chargeback\ChargebackResource;
use App\Responses\ResponseBuilder;
use App\Services\Chargeback\Contracts\ChargebackServiceContract;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;

class StoreChargebackController extends Controller
{
    /**
     * @param  ChargebackServiceContract  $chargebackService
     * @param  Logger  $logger
     */
    public function __construct(
        private ChargebackServiceContract $chargebackService,
        private Logger $logger
    ) {
        //
    }

    /**
     * @param  int  $transactionId
     * @param  ChargebackStoreRequest  $request
     * @return JsonResponse
     */
    public function __invoke(int $transactionId, ChargebackStoreRequest $request): JsonResponse
    {
        $responseBuilder = new ResponseBuilder();

        try {
            $data = $request->validated();

            $chargeback = $this->chargebackService
                ->handleChargeback(
                    $transactionId,
                    $data['reason'] ?? null
                );

            $chargebackResource = new ChargebackResource($chargeback);

            return $responseBuilder->message('New chargeback created')
                ->data($chargebackResource->make($chargeback))
                ->status(Response::HTTP_CREATED)
                ->build();
        } catch (TransactionAlreadyHasChargebackException $exception) {
            $this->logger->error($exception->getMessage(), [
                'code' => 'transaction_already_has_chargeback',
                'exception' => $exception,
                'request' => $request,
                'transaction_id' => $transactionId,
            ]);

            return $responseBuilder->message($exception->getMessage())
                ->status($exception->getCode())
                ->build();
        } catch (ModelNotFoundException $exception) {
            $this->logger->error('Transaction not found', [
                'code' => 'transaction_id_not_found',
                'exception' => $exception,
                'request' => $request,
                'transaction_id' => $transactionId,
            ]);

            return $responseBuilder->message('Transaction not found')
                ->status(Response::HTTP_NOT_FOUND)
                ->build();
        } catch (Exception $exception) {
            $this->logger->critical('Unexpected error in '.self::class, [
                'code' => 'unexpected_error',
                'exception' => $exception,
                'request' => $request,
                'transaction_id' => $transactionId,
            ]);

            return $responseBuilder->message('Unexpected error in '.self::class)
                ->status(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->build();
        }
    }
}
