<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait RestExceptionHandlerTrait
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        switch (true) {
            case $this->isModelNotFoundException($e):
                $retval = $this->modelNotFound();
                break;
            case $this->isValidationException($e):
                $retval = $this->validationException($e);
                break;
            default:
                $retval = $this->badRequest($e);
        }

        return $retval;
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest(Exception $e)
    {
        return $this->jsonResponse([
            'error' => true,
            'errorCode' => 'badRequest',
            'errorMessage' => $e->getMessage()
        ], 400);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound()
    {
        return $this->jsonResponse([
            'error' => true,
            'errorCode' => 'recordNotFound'
        ], 404);
    }

    protected function validationException(ValidationException $e)
    {
        /** @var \Illuminate\Validation\Validator $val */
        $val = $e->validator;

        return $this->jsonResponse([
            'error' => true,
            'errorCode' => 'validationError',
            'data' => $val->attributes(),
            'validatorMessages' => $e->validator->errors()
        ], 400);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload = null, $statusCode = 404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    protected function isValidationException(Exception $e)
    {
        return $e instanceof ValidationException;
    }

}
