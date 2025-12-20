<?php

namespace App\Repositories\Api\Role;

use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    /**
     * Get all roles with pagination
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Role::latest()->paginate($perPage);
    }

    /**
     * Get all roles without pagination
     */
    public function getAllWithoutPagination(): Collection
    {
        return Role::latest()->get();
    }

    /**
     * Find role by ID
     */
    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }

    /**
     * Create new role
     */
    public function create(array $data): Role
    {
        return Role::create([
            'role' => $data['role'],
            'permession' => json_encode($data['permissions']),
        ]);
    }

    /**
     * Update role
     */
    public function update(Role $role, array $data): bool
    {
        return $role->update([
            'role' => $data['role'],
            'permession' => json_encode($data['permissions']),
        ]);
    }

    /**
     * Delete role
     */
    public function delete(Role $role): bool
    {
        return $role->delete();
    }

    /**
     * Check if role has users
     */
    public function hasUsers(Role $role): bool
    {
        return $role->admins()->count() > 0 ||
            $role->users()->count() > 0;
    }
}
