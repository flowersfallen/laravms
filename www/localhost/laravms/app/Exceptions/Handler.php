<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Traits\ResponseTrait;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->formatReturn([
                'state' => false,
                'error' => '请求方式有误'
            ]);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->formatReturn([
                'state' => false,
                'error' => '请先登录',
                'code' => 50012
            ]);
        }

        if ($exception instanceof PermissionDeniedException) {
            return $this->formatReturn([
                'state' => false,
                'error' => '对应用户无权限'
            ]);
        }

        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }
        $msg = '';
        foreach ($e->errors() as $errors) {
            foreach ($errors as $error) {
                $msg .= $error;
            }
        }
        $send = [
            'state' => false,
            'error' => $msg
        ];
        return $this->formatReturn($send);
    }
}
