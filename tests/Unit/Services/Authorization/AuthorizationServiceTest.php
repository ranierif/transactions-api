<?php

namespace Tests\Unit\Services\Authorization;

use App\Services\Authorization\AuthorizationService;
use App\Services\Authorization\Contracts\AuthorizationServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class AuthorizationServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_receive_authorized_from_authorization_api(): void
    {
        // Arrange
        $this->mockAuthorizationServiceSuccess();

        // Act
        $verify = app(AuthorizationServiceContract::class)->verify();

        // Assert
        $this->assertEquals($verify, $this->mockAuthorizationResponseSuccess());
    }

    /**
     * @return void
     */
    private function mockAuthorizationServiceSuccess(): void
    {
        $this->instance(
            AuthorizationServiceContract::class,
            Mockery::mock(AuthorizationService::class, function (MockInterface $mock) {
                return $mock->shouldReceive('verify')
                    ->andReturn($this->mockAuthorizationResponseSuccess());
            })
        );
    }

    /**
     * @return array
     */
    private function mockAuthorizationResponseSuccess(): array
    {
        return [
            'success' => true,
            'message' => 'Autorizado',
        ];
    }

    /**
     * @return void
     */
    public function test_receive_unauthorized_from_authorization_api(): void
    {
        // Arrange
        $this->mockAuthorizationServiceError();

        // Act
        $verify = app(AuthorizationServiceContract::class)->verify();

        // Assert
        $this->assertEquals($verify, $this->mockAuthorizationResponseError());
    }

    /**
     * @return void
     */
    private function mockAuthorizationServiceError(): void
    {
        $this->instance(
            AuthorizationServiceContract::class,
            Mockery::mock(AuthorizationService::class, function (MockInterface $mock) {
                return $mock->shouldReceive('verify')
                    ->andReturn($this->mockAuthorizationResponseError());
            })
        );
    }

    /**
     * @return array
     */
    private function mockAuthorizationResponseError(): array
    {
        return [
            'success' => false,
            'message' => 'Unauthorized',
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
        $verify = app(AuthorizationServiceContract::class)->verify();

        // Assert
        $this->assertEquals($verify, $this->mockAuthorizationResponseConnectionError());
    }

    /**
     * @return void
     */
    private function mockAuthorizationServiceConnectionError(): void
    {
        $this->instance(
            AuthorizationServiceContract::class,
            Mockery::mock(AuthorizationService::class, function (MockInterface $mock) {
                return $mock->shouldReceive('verify')
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
