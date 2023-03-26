<?php

namespace App\Jobs\Notification;

use App\Exceptions\Notification\NotificationToPayeeNotSendedException;
use App\Models\Transaction;
use App\Services\Notification\Contracts\NotificationServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Log\Logger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var int
     */
    private const TRIES_TIMES = 10;

    /**
     * @var int
     */
    private const RETRY_IN = 300;

    /**
     * @var int
     */
    public $tries = self::TRIES_TIMES;

    /**
     * @param Transaction $transaction
     */
    public function __construct(public Transaction $transaction)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            app(NotificationServiceContract::class)->send();
        } catch (NotificationToPayeeNotSendedException $exception) {
            app(Logger::class)->error($exception->getMessage(), [
                'code' => 'notification_to_payee_not_sended',
                'exception' => $exception,
            ]);

            $this->release(self::RETRY_IN);
        }
    }

    /**
     * @return string[]
     */
    public function tags()
    {
        return [
            'SendNotificationJob',
            $this->transaction->id
        ];
    }
}
