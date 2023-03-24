<?php

namespace App\Http\Controllers\Transaction;

use App\Exceptions\Authorization\UnauthorizedToStoreTransactionException;
use App\Exceptions\Notification\NotificationToPayeeNotSendedException;
use App\Exceptions\Transaction\InsufficientFundsToSendException;
use App\Exceptions\Transaction\PayerCannotSendTransactionsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Responses\ResponseBuilder;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;

class StoreTransactionController extends Controller
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
     * @param  TransactionStoreRequest  $request
     * @return JsonResponse
     */
    public function __invoke(TransactionStoreRequest $request): JsonResponse
    {
        $responseBuilder = new ResponseBuilder();

        try {
            $data = $request->validated();
            $payerId = $data['payer_id'];
            $payeeId = $data['payee_id'];
            $value = $data['value'];

            $transaction = $this->transactionService
                ->handleNewTransaction(
                    $payerId,
                    $payeeId,
                    $value
                );

            $transactionResource = new TransactionResource($transaction);

            return $responseBuilder->message('New transaction created')
                ->data($transactionResource->make($transaction))
                ->status(Response::HTTP_CREATED)
                ->build();
        } catch (PayerCannotSendTransactionsException $exception) {
            $this->logger->error($exception->getMessage(), [
                'code' => 'payer_cannot_send_transaction',
                'exception' => $exception,
                'request' => $request,
            ]);

            return $responseBuilder->message($exception->getMessage())
                ->status($exception->getCode())
                ->build();
        } catch (InsufficientFundsToSendException $exception) {
            $this->logger->error($exception->getMessage(), [
                'code' => 'insufficient_funds_to_send',
                'exception' => $exception,
                'request' => $request,
            ]);

            return $responseBuilder->message($exception->getMessage())
                ->status($exception->getCode())
                ->build();
        } catch (UnauthorizedToStoreTransactionException $exception) {
            $this->logger->error($exception->getMessage(), [
                'code' => 'unauthorized_store_transaction',
                'exception' => $exception,
                'request' => $request,
            ]);

            return $responseBuilder->message($exception->getMessage())
                ->status($exception->getCode())
                ->build();
        } catch(NotificationToPayeeNotSendedException $exception) {
            $this->logger->error($exception->getMessage(), [
                'code' => 'notification_to_payee_not_sended',
                'exception' => $exception,
                'request' => $request,
            ]);

            return $responseBuilder->message($exception->getMessage())
                ->status($exception->getCode())
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
