<?php

namespace App\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResponseBuilder
{
    /**
     * @var mixed
     */
    private $response;

    /**
     * @var int
     */
    private $status = Response::HTTP_OK;

    /**
     * @return ResponseBuilder
     */
    public static function make(): ResponseBuilder
    {
        return new static();
    }

    /**
     * @param  string  $message
     * @return ResponseBuilder
     */
    public function message(string $message)
    {
        $this->response['message'] = $message;

        return $this;
    }

    /**
     * @param    $errors
     * @return ResponseBuilder
     */
    public function errors($errors)
    {
        $this->response['errors'] = $errors;

        return $this;
    }

    /**
     * @param    $data
     * @return ResponseBuilder
     */
    public function data($data)
    {
        $this->response['data'] = $data;

        return $this;
    }

    /**
     * @param  int  $status
     * @return ResponseBuilder
     */
    public function status(int $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function build(): JsonResponse
    {
        return response()->json($this->response, $this->status);
    }
}
