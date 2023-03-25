<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\TransactionResource;
use App\Responses\ResponseBuilder;
use App\Services\Transaction\Contracts\GetTransactionServiceContract;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;

class GetTransactionController extends Controller
{
    /**
     * @param  GetTransactionServiceContract  $getTransaction
     * @param  Logger  $logger
     */
    public function __construct(
        private GetTransactionServiceContract $getTransaction,
        private Logger $logger
    ) {
        //
    }

    /**
     * @param  int  $transactionId
     * @return JsonResponse
     */
    public function __invoke(int $transactionId): JsonResponse
    {
        $responseBuilder = new ResponseBuilder();

        try {
            $transaction = $this->getTransaction
                ->getTransactionById(
                    $transactionId
                );

            $transactionResource = new TransactionResource($transaction);

            return $responseBuilder->data($transactionResource->make($transaction))
                ->status(Response::HTTP_FOUND)
                ->build();
        } catch(ModelNotFoundException $exception) {
            $this->logger->error('Transaction not found', [
                'code' => 'transaction_not_found',
                'exception' => $exception,
                'transaction_id' => $transactionId,
            ]);

            return $responseBuilder->message('Transaction not found')
                ->status(Response::HTTP_NOT_FOUND)
                ->build();
        } catch (Exception $exception) {
            $this->logger->critical('Unexpected error in '.self::class, [
                'code' => 'unexpected_error',
                'exception' => $exception,
                'transaction_id' => $transactionId,
            ]);

            return $responseBuilder->message('Unexpected error in '.self::class)
                ->status(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->build();
        }
    }
}
