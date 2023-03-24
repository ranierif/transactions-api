<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionListRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Responses\ResponseBuilder;
use App\Services\Transaction\Contracts\GetTransactionServiceContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;

class ListTransactionController extends Controller
{
    /**
     * @param  GetTransactionServiceContract  $getTransactionService
     * @param  Logger  $logger
     */
    public function __construct(
        private GetTransactionServiceContract $getTransactionService,
        private Logger $logger
    ) {
        //
    }

    /**
     * @param  TransactionListRequest  $request
     * @return JsonResponse
     */
    public function __invoke(TransactionListRequest $request): JsonResponse
    {
        $responseBuilder = new ResponseBuilder();

        try {
            $transactions = $this->getTransactionService
                ->getTransactions(
                    $request->validated()
                );

            $transactionsResource = new TransactionResource($transactions);

            return $responseBuilder->data($transactionsResource->collection($transactions))
                ->status(Response::HTTP_FOUND)
                ->build();
        } catch (Exception $exception) {
            $this->logger->critical('Unexpected error in '.self::class, [
                'code' => 'unexpected_error',
                'exception' => $exception,
                'request' => $request,
            ]);

            return $responseBuilder->message('Unexpected error in '.self::class)
                ->status(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->build();
        }
    }
}
