<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Exception;


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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        /*if ($request->is('api/*')) {
        $header = $request->headers->get('Authorization');
        if ($header == '' || $header == null) {
            return $this->error('There is something wrong with Authorization header : Missing Authorization Header or Invalid Bearer token.',
             Response::HTTP_UNAUTHORIZED);
        }
        }*/
        
        if ($exception instanceof RouteNotFoundException) {
            return $this->error('Internal Server Error', 500);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->error('The specified method for the request is invalid', 405);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->error('The specified URL cannot be found', 404);
        }

        if ($exception instanceof HttpException) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);            
        }
        // Return Exception If Authorization header is empty or invalid token
       
        // Else there is Internal Server Error , 500
        return $this->error('Internal Server Error', 500);


       

       



    }
}
