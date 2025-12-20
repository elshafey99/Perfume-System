<?php

namespace App\Services\Api\User;

use App\Repositories\Api\User\UserRepository;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users
     */
    public function getAll(?int $perPage = null, ?string $type = null, ?bool $status = null): array
    {
        if ($perPage) {
            $users = $this->userRepository->getAll($perPage, $type, $status);
        } else {
            $users = $this->userRepository->getAllWithoutPagination($type, $status);
        }

        return [
            'success' => true,
            'data' => $users,
        ];
    }

    /**
     * Get user by ID
     */
    public function getById(int $id): array
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            return [
                'success' => false,
                'message' => __('users.user_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $user,
        ];
    }

    /**
     * Create new user
     */
    public function create(array $data): array
    {
        // Check if email already exists
        if ($this->userRepository->emailExists($data['email'])) {
            return [
                'success' => false,
                'message' => __('users.email_already_exists'),
            ];
        }

        // Check if phone already exists (if provided)
        if (isset($data['phone']) && !empty($data['phone'])) {
            if ($this->userRepository->phoneExists($data['phone'])) {
                return [
                    'success' => false,
                    'message' => __('users.phone_already_exists'),
                ];
            }
        }

        try {
            $user = $this->userRepository->create($data);

            return [
                'success' => true,
                'data' => $user->load('role'),
                'message' => __('users.user_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('users.user_creation_failed'),
            ];
        }
    }

    /**
     * Update user
     */
    public function update(int $id, array $data): array
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            return [
                'success' => false,
                'message' => __('users.user_not_found'),
            ];
        }

        // Check if email already exists (excluding current user)
        if (isset($data['email']) && $data['email'] !== $user->email) {
            if ($this->userRepository->emailExists($data['email'], $user->id)) {
                return [
                    'success' => false,
                    'message' => __('users.email_already_exists'),
                ];
            }
        }

        // Check if phone already exists (excluding current user)
        if (isset($data['phone']) && !empty($data['phone']) && $data['phone'] !== $user->phone) {
            if ($this->userRepository->phoneExists($data['phone'], $user->id)) {
                return [
                    'success' => false,
                    'message' => __('users.phone_already_exists'),
                ];
            }
        }

        try {
            $this->userRepository->update($user, $data);

            return [
                'success' => true,
                'data' => $user->fresh()->load('role'),
                'message' => __('users.user_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('users.user_update_failed'),
            ];
        }
    }

    /**
     * Delete user
     */
    public function delete(int $id): array
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            return [
                'success' => false,
                'message' => __('users.user_not_found'),
            ];
        }

        try {
            $this->userRepository->delete($user);

            return [
                'success' => true,
                'message' => __('users.user_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('users.user_deletion_failed'),
            ];
        }
    }

    /**
     * Change user status
     */
    public function changeStatus(int $id, bool $status): array
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            return [
                'success' => false,
                'message' => __('users.user_not_found'),
            ];
        }

        try {
            $this->userRepository->updateStatus($user, $status);

            return [
                'success' => true,
                'data' => $user->fresh()->load('role'),
                'message' => $status
                    ? __('users.user_activated_successfully')
                    : __('users.user_deactivated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('users.user_status_update_failed'),
            ];
        }
    }
}
