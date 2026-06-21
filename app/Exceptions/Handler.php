<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle ValidationException
        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang dikirim tidak valid',
                    'error_code' => 'VALIDATION_ERROR',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Handle ModelNotFoundException
        $this->renderable(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                    'error_code' => 'NOT_FOUND',
                ], 404);
            }
        });

        // Handle AuthorizationException
        $this->renderable(function (AuthorizationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke modul ini',
                    'error_code' => 'PERMISSION_DENIED',
                ], 403);
            }
        });

        // Handle CertificationRequiredException
        $this->renderable(function (CertificationRequiredException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error_code' => 'CERTIFICATION_REQUIRED',
                ], 422);
            }
        });

        // Handle UnverifiedBankAccountException
        $this->renderable(function (UnverifiedBankAccountException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error_code' => 'UNVERIFIED_BANK_ACCOUNT',
                ], 422);
            }
        });

        // Handle PayrollPeriodLockedException
        $this->renderable(function (PayrollPeriodLockedException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error_code' => 'PAYROLL_PERIOD_LOCKED',
                ], 422);
            }
        });

        // Handle generic Throwable (500 errors)
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') && !config('app.debug')) {
                report($e);

                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan pada server',
                    'error_code' => 'INTERNAL_SERVER_ERROR',
                ], 500);
            }
        });
    }
}
