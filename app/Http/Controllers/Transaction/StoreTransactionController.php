<?php

namespace App\Http\Controllers\Transaction;

use App\Exceptions\Authorization\UnauthorizedToStoreTransactionException;
use App\Exceptions\Transaction\InsufficientFundsToSendTransactionException;
use App\Exceptions\Transaction\PayerCannotSendTransactionsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Responses\ResponseBuilder;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class StoreTransactionController extends Controller
{
    /**
     * @param  TransactionServiceContract  $transactionService
     */
    public function __construct(private TransactionServiceContract $transactionService)
    {
        //
    }

    /**
     * @param  TransactionStoreRequest  $request
     * @return JsonResponse
     */
    public function __invoke(TransactionStoreRequest $request): JsonResponse
    {
        $response = ResponseBuilder::init();

        try {
            $data = $request->validated();
            $payerId = Arr::get($data, 'payer_id');
            $payeeId = Arr::get($data, 'payee_id');
            $value = Arr::get($data, 'value');

            $transaction = $this->transactionService
                ->handleNewTransaction(
                    $payerId,
                    $payeeId,
                    $value
                );

            return $response->message('New transaction created')
                ->data(TransactionResource::make($transaction))
                ->status(Response::HTTP_CREATED)
                ->build();
        } catch (PayerCannotSendTransactionsException $exception) {
            Log::error($exception->getMessage(), [
                'code' => 'payer_cannot_send_transaction',
                'exception' => $exception,
                'request' => $request,
            ]);

            return $response->message($exception->getMessage())
                ->status($exception->getCode())
                ->build();
        } catch (InsufficientFundsToSendTransactionException $exception) {
            Log::error($exception->getMessage(), [
                'code' => 'insufficient_funds_to_send',
                'exception' => $exception,
                'request' => $request,
            ]);

            return $response->message($exception->getMessage())
                ->status($exception->getCode())
                ->build();
        } catch (UnauthorizedToStoreTransactionException $exception) {
            Log::error($exception->getMessage(), [
                'code' => 'unauthorized_store_transaction',
                'exception' => $exception,
                'request' => $request,
            ]);

            return $response->message($exception->getMessage())
                ->status($exception->getCode())
                ->build();
        } catch (Exception $exception) {
            Log::critical('Unexpected error in '.self::class, [
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
