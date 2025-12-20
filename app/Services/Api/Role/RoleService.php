<?php

namespace App\Services\Api\Role;

use App\Repositories\Api\Role\RoleRepository;
use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleService
{
    protected RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get all roles
     */
    public function getAll(?int $perPage = null): array
    {
        if ($perPage) {
            $roles = $this->roleRepository->getAll($perPage);
        } else {
            $roles = $this->roleRepository->getAllWithoutPagination();
        }

        return [
            'success' => true,
            'data' => $roles,
        ];
    }

    /**
     * Get role by ID
     */
    public function getById(int $id): array
    {
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            return [
                'success' => false,
                'message' => __('roles.role_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $role,
        ];
    }

    /**
     * Get available permissions
     */
    public function getPermissions(): array
    {
        $permissionsAr = config('permessions_ar', []);

        $permissions = [];
        foreach ($permissionsAr as $key => $arValue) {
            $permissions[] = [
                'key' => $key,
                'name' => $arValue,
            ];
        }

        return [
            'success' => true,
            'data' => $permissions,
        ];
    }

    /**
     * Create new role
     */
    public function create(array $data): array
    {
        try {
            $role = $this->roleRepository->create($data);

            return [
                'success' => true,
                'data' => $role,
                'message' => __('roles.role_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('roles.role_creation_failed'),
            ];
        }
    }

    /**
     * Update role
     */
    public function update(int $id, array $data): array
    {
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            return [
                'success' => false,
                'message' => __('roles.role_not_found'),
            ];
        }

        try {
            $this->roleRepository->update($role, $data);

            return [
                'success' => true,
                'data' => $role->fresh(),
                'message' => __('roles.role_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('roles.role_update_failed'),
            ];
        }
    }

    /**
     * Delete role
     */
    public function delete(int $id): array
    {
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            return [
                'success' => false,
                'message' => __('roles.role_not_found'),
            ];
        }

        // Check if role has users
        if ($this->roleRepository->hasUsers($role)) {
            return [
                'success' => false,
                'message' => __('roles.role_has_users'),
            ];
        }

        try {
            $this->roleRepository->delete($role);

            return [
                'success' => true,
                'message' => __('roles.role_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('roles.role_deletion_failed'),
            ];
        }
    }
}

