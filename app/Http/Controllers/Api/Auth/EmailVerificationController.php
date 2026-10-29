<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Resend\Exceptions\ErrorException as ResendErrorException;
use Throwable;

class EmailVerificationController extends Controller
{
    public function status(Request $request): JsonResponse
    {
        return response()->json([
            'verified' => $request->user()?->hasVerifiedEmail() ?? false,
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email address is already verified.',
            ]);
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (Throwable $exception) {
            $mailer = (string) config('mail.default');

            Log::error('Verification email resend failed.', [
                'user_id' => $request->user()?->id,
                'mailer' => $mailer,
                'exception' => $exception::class,
                'cause' => $exception->getPrevious() ? $exception->getPrevious()::class : null,
                'provider_http_status' => $this->providerHttpStatus($exception),
                'resend_api_key_configured' => is_string(config('services.resend.key'))
                    && config('services.resend.key') !== '',
            ]);

            return response()->json([
                'message' => 'Verification email could not be sent. Check the server mail configuration and try again later.',
            ], 503);
        }

        return response()->json([
            'message' => 'Verification link sent.',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user() ? new UserResource($request->user()) : null,
        ]);
    }

    private function providerHttpStatus(Throwable $exception): ?int
    {
        for ($current = $exception; $current !== null; $current = $current->getPrevious()) {
            if ($current instanceof ResendErrorException) {
                $status = $current->getErrorCode();

                if ($status >= 100 && $status <= 599) {
                    return $status;
                }
            }

            if (method_exists($current, 'getResponse')) {
                $response = $current->getResponse();

                if (is_object($response) && method_exists($response, 'getStatusCode')) {
                    $status = $response->getStatusCode();

                    if (is_int($status) && $status >= 100 && $status <= 599) {
                        return $status;
                    }
                }
            }
        }

        return null;
    }
}
