<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionListRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Responses\ResponseBuilder;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;

class ListTransactionController extends Controller
{
    /**
     * @param  TransactionServiceContract  $transactionService
     * @param  Logger  $logger
     */
    public function __construct(
        private TransactionServiceContract $transactionService,
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
        $response = ResponseBuilder::init();

        try {
            $transactions = $this->transactionService
                ->getTransactions(
                    $request->validated()
                );

            return $response->data(TransactionResource::collection($transactions))
                ->status(Response::HTTP_FOUND)
                ->build();
        } catch (Exception $exception) {
            $this->logger->critical('Unexpected error in '.self::class, [
                'code' => 'unexpected_error',
                'exception' => $exception,
                'request' => $request,
            ]);

            return $response->message('Unexpected error in '.self::class)
                ->status(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->build();
        }
    }
}
