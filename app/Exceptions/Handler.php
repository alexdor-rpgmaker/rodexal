<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Application|JsonResponse|RedirectResponse|Response|Redirector
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (!Auth::check() && $exception instanceof AuthorizationException) {
            $request->session()->put('urlBeforeLogin', $request->getRequestUri());
            return redirect(route('oauth'));
        }

        if ($request->expectsJson() && $exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            return response()->json([
                'code' => $statusCode,
                'message' => $exception->getMessage()
            ], $statusCode);
        }

        return parent::render($request, $exception);
    }
}
