<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson() || $request->expectsJson()) { 
            return $this->handleApiException($request, $exception);
        } else {
            return parent::render($request, $exception);
        }
    }


    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }


    private function customApiResponse($exception)
    {
        $response = [];
        $statusCode = 500;
        if (method_exists($exception, 'getCode') || method_exists($exception, 'getStatusCode')) {
            $statusCode =  ($exception->getCode() > 0 && $exception->getCode() < 500) ? $exception->getCode() :  $exception->getStatusCode();
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = " " . strtolower(class_basename($exception->getModel())) . " ";
            $response['message'] = "Does not exist Any ". $modelName . " with this Identifier ";
        }
        elseif ($exception instanceof AuthorizationException) {
            $response['message'] = $exception->getMessage();
        }
        elseif ($exception instanceof NotFoundHttpException) {
            $response['message'] = 'Model Not Found Http Excption';
        }
        elseif ($exception instanceof MethodNotAllowedHttpException) {
            $response['message'] = 'Method Not Allowed';
        }
        elseif ($exception instanceof HttpException) {
            $response['message'] = 'Http Exception Error';
        }
        elseif ($exception instanceof QueryException) {
            $response['message'] = $exception->getMessage();
        }
        elseif ($exception instanceof TokenMismatchException) {
           $response['message'] =  'Csrf token mis match';
        }
        elseif ($exception instanceof UnprocessableEntityHttpException) {
            $response['message'] =  $exception->getMessage();
        }else{
            $response['message'] = $exception->getMessage();
        }

        $response['status'] = $statusCode;

        return response()->json($response, $statusCode);
    }
}
