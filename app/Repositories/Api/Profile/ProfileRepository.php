<?php

namespace App\Repositories\Api\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\FileHelper;

class ProfileRepository
{
    /**
     * Get user profile
     */
    public function getProfile(User $user): User
    {
        return $user->load('role');
    }

    /**
     * Update user profile
     */
    public function updateProfile(User $user, array $data): bool
    {
        $updateData = [];

        // Update name if provided
        if (isset($data['name']) && $data['name'] !== '') {
            $updateData['name'] = $data['name'];
        }

        // Update email if provided
        if (isset($data['email']) && $data['email'] !== '') {
            $updateData['email'] = $data['email'];
        }

        // Update phone if provided (can be null to clear it)
        if (array_key_exists('phone', $data)) {
            $updateData['phone'] = $data['phone'];
        }

        // Update position if provided (can be null to clear it)
        if (array_key_exists('position', $data)) {
            $updateData['position'] = $data['position'];
        }

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $oldImage = $user->image;
            $newImage = FileHelper::uploadImage($data['image'], 'uploads/users', $oldImage);
            if ($newImage) {
                $updateData['image'] = $newImage;
            }
        }

        // Only update if there's data to update
        if (empty($updateData)) {
            return true; // Nothing to update
        }

        $updated = $user->update($updateData);

        // Refresh the user model to get updated data
        $user->refresh();

        return $updated;
    }

    /**
     * Change user password
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        // Verify current password
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        // Update password
        return $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Check if email is unique (excluding current user)
     */
    public function isEmailUnique(string $email, int $userId): bool
    {
        return !User::where('email', $email)
            ->where('id', '!=', $userId)
            ->exists();
    }
}
