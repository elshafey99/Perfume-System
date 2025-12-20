<?php

namespace App\Repositories\Api\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository
{
    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Verify user credentials
     */
    public function verifyCredentials(string $email, string $password): ?User
    {
        $user = $this->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        // Check if user is active
        if (!$user->status) {
            return null;
        }

        return $user;
    }

    /**
     * Generate and store verification code
     */
    public function generateVerificationCode(User $user): string
    {
        $code = str_pad((string) rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'verification_code' => Hash::make($code),
            'verification_code_expires_at' => now()->addMinutes(15),
        ]);

        return $code;
    }

    /**
     * Verify reset code
     */
    public function verifyResetCode(string $email, string $code): ?User
    {
        $user = $this->findByEmail($email);

        if (!$user || !$user->verification_code) {
            return null;
        }

        // Check if code is expired
        if ($user->verification_code_expires_at && $user->verification_code_expires_at->isPast()) {
            return null;
        }

        // Verify code
        if (!Hash::check($code, $user->verification_code)) {
            return null;
        }

        return $user;
    }

    /**
     * Verify code only (without resetting password)
     */
    public function verifyCodeOnly(string $email, string $code): ?User
    {
        return $this->verifyResetCode($email, $code);
    }

    /**
     * Reset user password (after code is verified)
     */
    public function resetPassword(User $user, string $newPassword): bool
    {
        return $user->update([
            'password' => Hash::make($newPassword),
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);
    }

    /**
     * Revoke all user tokens
     */
    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Create new token for user
     */
    public function createToken(User $user, string $tokenName = 'auth_token'): string
    {
        // Revoke old tokens
        $this->revokeAllTokens($user);

        return $user->createToken($tokenName)->plainTextToken;
    }
}
