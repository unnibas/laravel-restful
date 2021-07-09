<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

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
        'current_password',
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

        $this->renderable(function (ValidationException $e) {
            return response()->json($e->errors(), 422);
        });

        $this->renderable(function (ModelNotFoundException $e){
            $model = strtolower(class_basename($e->getModel()));
            return $this->errorResponse("does not exist any {$model} model with specified identifier", 404);
        });

        $this->renderable(function (AuthenticationException $e){
            return $this->errorResponse("Unauthenticated", 401);
        });

        $this->renderable(function (AuthorizationException $e){
            return $this->errorResponse($e->getMessage(), 403);
        });

        $this->renderable(function(NotFoundHttpException $e){
            return $this->errorResponse("the specified URL cannot be found", 404);
        });

        $this->renderable(function(MethodNotAllowedHttpException $e){
            return $this->errorResponse("the specified method for the request is invalid", 405);
        });

        $this->renderable(function(HttpException $e){
            return $this->errorResponse($e->getMessage(), $e->getCode());
        });

        $this->renderable(function(QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1451)
                return $this->errorResponse("cannot remove this resource permanently. It is related with any other resource", 409);
        });

        if(!config('app.debug')){
            $this->renderable(function(UnexpectedTypeException $e){
                return $this->errorResponse("Unexpected Exception. Try Later", 500);
            });
        }

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
