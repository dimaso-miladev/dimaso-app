<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? $this->jsonError($exception->getMessage() ?: 'Unauthenticated.', 401)
            : redirect()->guest(url('/login'));
    }

    /**
     * Prepare a JSON response for the given exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareJsonResponse($request, Throwable $e)
    {
        // Keep parent behavior; all formatting is done in render() for simplicity.
        return parent::prepareJsonResponse($request, $e);
    }

    /**
     * Format validation errors as unified JSON.
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return $this->jsonError($exception->getMessage(), $exception->status, $exception->errors());
    }

    /**
     * Render exceptions to HTTP responses with a simple, consistent JSON body.
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            if ($e instanceof ValidationException) {
                return $this->invalidJson($request, $e);
            }
            if ($e instanceof AuthenticationException) {
                return $this->unauthenticated($request, $e);
            }
            if ($e instanceof AuthorizationException) {
                return $this->jsonError('This action is unauthorized.', 403);
            }
            if ($e instanceof ModelNotFoundException) {
                return $this->jsonError('Resource not found.', 404);
            }
            if ($e instanceof NotFoundHttpException) {
                return $this->jsonError('Route not found.', 404);
            }
            if ($e instanceof MethodNotAllowedHttpException) {
                return $this->jsonError('Method not allowed.', 405);
            }
            if ($e instanceof InvalidSignatureException) {
                return $this->jsonError('Invalid or expired signature.', 403);
            }
            if ($e instanceof HttpResponseException) {
                return $e->getResponse();
            }
            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $message = $e->getMessage() ?: 'HTTP error.';
                return $this->jsonError($message, $status);
            }

            $message = config('app.debug') ? ($e->getMessage() ?: 'Server error.') : 'Server error.';
            return $this->jsonError($message, 500);
        }

        return parent::render($request, $e);
    }

    /**
     * Helper to build a unified error JSON payload.
     */
    private function jsonError(string $message, int $status, $details = null): JsonResponse
    {
        $body = [
            'success' => false,
            'error' => [ 'message' => $message ],
        ];

        if ($details !== null) {
            $body['error']['details'] = $details;
        }

        return response()->json($body, $status);
    }
}
