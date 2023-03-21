<?php

namespace Tests\Feature\User;

use App\Http\Resources\User\UserResource;
use App\Services\User\Contracts\UserServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserListControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var string
     */
    private const USER_LIST_ROUTE_NAME = 'users';

    /**
     * @var UserServiceContract
     */
    private $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userService = app()->make(UserServiceContract::class);
    }

    /**
     * @return void
     */
    public function test_list_all_users(): void
    {
        // Arrange
        $users =  $this->userService->getUsers([]);
        $resource = UserResource::collection($users);
        $request = Request::create(route(self::USER_LIST_ROUTE_NAME), 'GET');

        // Act
        $response = $this->get(route(self::USER_LIST_ROUTE_NAME));

        // Assert
        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson([
            'data' => $resource->response($request)->getData(true)['data'],
        ]);
    }
}
