<?php

namespace Tests\Unit\Services\Notification;

use App\Services\Notification\NotificationService;
use App\Services\Notification\Contracts\NotificationServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_can_send_notification(): void
    {
        // Arrange
        $this->mockNotificationServiceSuccess();

        // Act
        $send = app(NotificationServiceContract::class)->send();

        // Assert
        $this->assertEquals($send, $this->mockNotificationResponseSuccess());
    }

    /**
     * @return void
     */
    private function mockNotificationServiceSuccess(): void
    {
        $this->instance(
            NotificationServiceContract::class,
            Mockery::mock(NotificationService::class, function (MockInterface $mock) {
                return $mock->shouldReceive('send')
                    ->andReturn($this->mockNotificationResponseSuccess());
            })
        );
    }

    /**
     * @return array
     */
    private function mockNotificationResponseSuccess(): array
    {
        return [
            'success' => true,
            'message' => 'Success',
        ];
    }

    /**
     * @return void
     */
    public function test_cannot_send_notification(): void
    {
        // Arrange
        $this->mockNotificationServiceError();

        // Act
        $send = app(NotificationServiceContract::class)->send();

        // Assert
        $this->assertEquals($send, $this->mockNotificationResponseError());
    }

    /**
     * @return void
     */
    private function mockNotificationServiceError(): void
    {
        $this->instance(
            NotificationServiceContract::class,
            Mockery::mock(NotificationService::class, function (MockInterface $mock) {
                return $mock->shouldReceive('send')
                    ->andReturn($this->mockNotificationResponseError());
            })
        );
    }

    /**
     * @return array
     */
    private function mockNotificationResponseError(): array
    {
        return [
            'success' => false,
            'message' => 'Fake error message',
        ];
    }

    /**
     * @return void
     */
    public function test_cannot_connect_to_authorization_api(): void
    {
        // Arrange
        $this->mockAuthorizationServiceConnectionError();

        // Act
        $send = app(AuthorizationServiceContract::class)->send();

        // Assert
        $this->assertEquals($send, $this->mockAuthorizationResponseConnectionError());
    }

    /**
     * @return void
     */
    private function mockAuthorizationServiceConnectionError(): void
    {
        $this->instance(
            AuthorizationServiceContract::class,
            Mockery::mock(AuthorizationService::class, function (MockInterface $mock) {
                return $mock->shouldReceive('send')
                    ->andReturn($this->mockAuthorizationResponseConnectionError());
            })
        );
    }

    /**
     * @return array
     */
    private function mockAuthorizationResponseConnectionError(): array
    {
        return [
            'success' => false,
            'message' => 'Couldn\'t connect to Authorization service',
        ];
    }
}
