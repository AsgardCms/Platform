<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\User\Contracts\Authentication;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Contracts\Container\Container;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(Container $container, Authentication $auth)
    {
        parent::__construct($container);
        $this->auth = $auth;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (config('app.debug') === false) {
            return $this->handleExceptions($e);
        }

        return parent::render($request, $e);
    }

    private function handleExceptions($e)
    {
        if ($e instanceof ModelNotFoundException) {
            if ($this->auth->check() === false) {
                return redirect()->route('login');
            }
            return response()->view('errors.404', [], 404);
        }

        if ($e instanceof NotFoundHttpException) {
            if ($this->auth->check() === false) {
                return redirect()->route('login');
            }
            return response()->view('errors.404', [], 404);
        }

        return response()->view('errors.500', [], 500);
    }
}
