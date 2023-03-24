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
use Illuminate\Log\Logger;

class UserListController extends Controller
{
    /**
     * @param  UserServiceContract  $userService
     * @param  Logger  $logger
     */
    public function __construct(
        private UserServiceContract $userService,
        private Logger $logger
    ) {
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
            $this->logger->critical('Unexpected error in '.self::class, [
                'code' => 'unexpected_error',
                'exception' => $exception,
            ]);

            return $response->message('Unexpected error in '.self::class)
                ->status(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->build();
        }
    }
}
