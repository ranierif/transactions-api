<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\TransactionResource;
use App\Responses\ResponseBuilder;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class GetTransactionController extends Controller
{
    /**
     * @param  TransactionServiceContract  $transactionService
     */
    public function __construct(private TransactionServiceContract $transactionService)
    {
        //
    }

    /**
     * @param  int  $transactionId
     * @return JsonResponse
     */
    public function __invoke(int $transactionId): JsonResponse
    {
        $response = ResponseBuilder::init();

        try {
            $transaction = $this->transactionService
                ->getTransactionById(
                    $transactionId
                );

            return $response->data(TransactionResource::make($transaction))
                ->status(Response::HTTP_FOUND)
                ->build();
        } catch(ModelNotFoundException $exception) {
            Log::error('Transaction not found', [
                'code' => 'transaction_not_found',
                'exception' => $exception,
                'transaction_id' => $transactionId,
            ]);

            return $response->message('Transaction not found')
                ->status(Response::HTTP_NOT_FOUND)
                ->build();
        } catch (Exception $exception) {
            Log::critical('Unexpected error in '.self::class, [
                'code' => 'unexpected_error',
                'exception' => $exception,
                'transaction_id' => $transactionId,
            ]);

            return $response->message('Unexpected error in '.self::class)
                ->status(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->build();
        }
    }
}
