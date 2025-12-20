<?php

namespace App\Repositories\Api\User;

use App\Models\User;
use App\Helpers\FileHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * Get all users with pagination
     */
    public function getAll(int $perPage = 15, ?string $type = null, ?bool $status = null): LengthAwarePaginator
    {
        $query = User::with('role')->latest();

        if ($type) {
            $query->where('type', $type);
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all users without pagination
     */
    public function getAllWithoutPagination(?string $type = null, ?bool $status = null): Collection
    {
        $query = User::with('role')->latest();

        if ($type) {
            $query->where('type', $type);
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User
    {
        return User::with('role')->find($id);
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Create new user
     */
    public function create(array $data): User
    {
        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $imagePath = FileHelper::uploadImage($data['image'], 'uploads/users');
            $data['image'] = $imagePath;
        } else {
            $data['image'] = 'uploads/images/image.png';
        }

        // Hash password
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return User::create($data);
    }

    /**
     * Update user
     */
    public function update(User $user, array $data): bool
    {
        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $oldImage = $user->image;
            $imagePath = FileHelper::uploadImage($data['image'], 'uploads/users', $oldImage);
            if ($imagePath) {
                $data['image'] = $imagePath;
            } else {
                unset($data['image']); // Keep old image if upload failed
            }
        }

        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Don't update password if not provided
        }

        return $user->update($data);
    }

    /**
     * Delete user
     */
    public function delete(User $user): bool
    {
        // Delete user image if exists and not default
        if ($user->image && $user->image !== 'uploads/images/image.png') {
            FileHelper::delete($user->image);
        }

        return $user->delete();
    }

    /**
     * Check if email exists (excluding current user)
     */
    public function emailExists(string $email, ?int $excludeUserId = null): bool
    {
        $query = User::where('email', $email);

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        return $query->exists();
    }

    /**
     * Check if phone exists (excluding current user)
     */
    public function phoneExists(string $phone, ?int $excludeUserId = null): bool
    {
        $query = User::where('phone', $phone);

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        return $query->exists();
    }

    /**
     * Update user status
     */
    public function updateStatus(User $user, bool $status): bool
    {
        return $user->update(['status' => $status]);
    }
}

