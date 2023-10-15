<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
        //return parent::render($request, $exception);
        if ($request->wantsJson() || $request->expectsJson()) { 
            return $this->handleApiException($request, $exception);
        } else {
            return parent::render($request, $exception);
        }
    }


    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        else if ($exception instanceof ModelNotFoundException) {
            $modelName = " " . strtolower(class_basename($exception->getModel())) . " ";
            $response['message'] = "Does not exist Any ". $modelName . " with this Identifier ";
            $statusCode =  404;
        }
        elseif ($exception instanceof AuthorizationException) {
            $response['message'] = "Unauthorized";
            $statusCode =  401;
        }
        elseif ($exception instanceof NotFoundHttpException) {
            $response['message'] = 'Model Not Found Http Excption';
            $statusCode =  404;
        }
        elseif ($exception instanceof MethodNotAllowedHttpException) {
            $response['message'] = $exception->getMessage() ?? 'Method Not Allowed';
            $statusCode =  405;
        }
        elseif ($exception instanceof HttpException) {
            $response['message'] = $exception->getMessage() ?? 'Http Exception Error';
            $statusCode =  405;
        }
        elseif ($exception instanceof QueryException) {
            $response['message'] = $exception->getMessage();
        }
        elseif ($exception instanceof TokenMismatchException) {
           $response['message'] =  'Csrf token mis match';
           $statusCode =  401;
        }
        elseif ($exception instanceof UnprocessableEntityHttpException) {
            $response['message'] =  "Unprocessable Entity";
            $statusCode =  422;
        } elseif ($exception instanceof BadRequestHttpException) {
            $response['message'] =  $exception->getMessage();
            $statusCode =  400;
        } elseif ($exception instanceof Exception) {
            $response['message'] =  $exception->getMessage();
            $statusCode =  $exception->getCode();
        }
        else{
            $response['message'] = "Oops, Something went wrong!!";
            $statusCode =  500;
        }

        return response()->json($response, $statusCode);
    }

}
