<?php

namespace App\Services\Api\Auth;

use App\Repositories\Api\Auth\AuthRepository;
use App\Notifications\PasswordResetCodeNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthService
{
    protected AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Login user
     */
    public function login(string $username, string $password): array
    {
        $user = $this->authRepository->verifyCredentials($username, $password);

        if (!$user) {
            return [
                'success' => false,
                'message' => __('auth.failed'),
            ];
        }

        $token = $this->authRepository->createToken($user);

        return [
            'success' => true,
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Send password reset code
     */
    public function sendPasswordResetCode(string $email): array
    {
        $user = $this->authRepository->findByEmail($email);

        if (!$user) {
            // Don't reveal if email exists or not for security
            return [
                'success' => true,
                'message' => __('auth.password_reset_code_sent'),
            ];
        }

        // Check if user is active
        if (!$user->status) {
            return [
                'success' => false,
                'message' => __('auth.account_inactive'),
            ];
        }

        $code = $this->authRepository->generateVerificationCode($user);

        // Send notification
        try {
            $user->notify(new PasswordResetCodeNotification($code));
        } catch (\Exception $e) {
            Log::error('Failed to send password reset code: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => __('auth.failed_to_send_code'),
            ];
        }

        return [
            'success' => true,
            'message' => __('auth.password_reset_code_sent'),
        ];
    }

    /**
     * Verify reset code
     */
    public function verifyCode(string $email, string $code): array
    {
        $user = $this->authRepository->verifyCodeOnly($email, $code);

        if (!$user) {
            return [
                'success' => false,
                'message' => __('auth.invalid_or_expired_code'),
            ];
        }

        return [
            'success' => true,
            'message' => __('auth.code_verified_success'),
        ];
    }

    /**
     * Resend password reset code
     */
    public function resendCode(string $email): array
    {
        return $this->sendPasswordResetCode($email);
    }

    /**
     * Reset password (after code is verified)
     */
    public function resetPassword(string $email, string $newPassword): array
    {
        $user = $this->authRepository->findByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => __('auth.user_not_found'),
            ];
        }

        // Check if user has a verified code
        if (!$user->verification_code) {
            return [
                'success' => false,
                'message' => __('auth.code_not_verified'),
            ];
        }

        $this->authRepository->resetPassword($user, $newPassword);

        return [
            'success' => true,
            'message' => __('auth.password_reset_success'),
        ];
    }

    /**
     * Logout user
     */
    public function logout(User $user): bool
    {
        $this->authRepository->revokeAllTokens($user);
        return true;
    }
}
