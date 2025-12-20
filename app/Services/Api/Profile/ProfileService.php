<?php

namespace App\Services\Api\Profile;

use App\Repositories\Api\Profile\ProfileRepository;
use App\Models\User;

class ProfileService
{
    protected ProfileRepository $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Get user profile
     */
    public function getProfile(User $user): array
    {
        $profile = $this->profileRepository->getProfile($user);

        return [
            'success' => true,
            'data' => $profile,
        ];
    }

    /**
     * Update user profile
     */
    public function updateProfile(User $user, array $data): array
    {
        // Check if email is unique
        if (isset($data['email']) && $data['email'] !== $user->email) {
            if (!$this->profileRepository->isEmailUnique($data['email'], $user->id)) {
                return [
                    'success' => false,
                    'message' => __('profile.email_already_exists'),
                ];
            }
        }

        try {
            $updated = $this->profileRepository->updateProfile($user, $data);

            if (!$updated) {
                return [
                    'success' => false,
                    'message' => __('profile.profile_update_failed'),
                ];
            }

            // Get fresh user data with role
            $updatedUser = User::with('role')->find($user->id);

            return [
                'success' => true,
                'data' => $updatedUser,
                'message' => __('profile.profile_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('profile.profile_update_failed') . ': ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Change user password
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): array
    {
        if (!$this->profileRepository->changePassword($user, $currentPassword, $newPassword)) {
            return [
                'success' => false,
                'message' => __('profile.current_password_incorrect'),
            ];
        }

        return [
            'success' => true,
            'message' => __('profile.password_changed_successfully'),
        ];
    }
}
