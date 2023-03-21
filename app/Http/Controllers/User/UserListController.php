<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserListRequest;
use App\Http\Resources\User\UserResource;
use App\Responses\ResponseBuilder;
use App\Services\User\Contracts\UserServiceContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserListController extends Controller
{
    /**
     * @param  UserServiceContract  $userService
     */
    public function __construct(private UserServiceContract $userService)
    {
        //
    }

    /**
     * @param  UserListRequest  $request
     * @return JsonResponse
     */
    public function __invoke(UserListRequest $request): JsonResponse
    {
        $response = ResponseBuilder::init();

        try {
            $users = $this->userService->getUsers($request->validated());

            return $response->data(UserResource::collection($users))
                ->status(Response::HTTP_OK)
                ->build();
        } catch (Exception $exception) {
            return $response->message('Unexpected error in '.self::class)
                ->status(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->build();
        }
    }
}
