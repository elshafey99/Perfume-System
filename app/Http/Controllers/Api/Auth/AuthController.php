<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\VerifyCodeRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Resources\Api\Auth\UserResource;
use App\Services\Api\Auth\AuthService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->validated()['email'],
            $request->validated()['password']
        );

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 401);
        }

        return ApiResponse::success([
            'user' => new UserResource($result['user']->load('role')),
            'token' => $result['token'],
        ], __('auth.login_success', [], app()->getLocale()));
    }

    /**
     * Send password reset code
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $result = $this->authService->sendPasswordResetCode(
            $request->validated()['email']
        );

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }

    /**
     * Verify reset code
     */
    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->authService->verifyCode(
            $validated['email'],
            $validated['code']
        );

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }

    /**
     * Resend password reset code
     */
    public function resendCode(ForgotPasswordRequest $request): JsonResponse
    {
        $result = $this->authService->resendCode(
            $request->validated()['email']
        );

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }

    /**
     * Reset password (after code is verified)
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->authService->resetPassword(
            $validated['email'],
            $validated['password']
        );

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return ApiResponse::success(null, __('auth.logout_success', [], app()->getLocale()));
    }
}
