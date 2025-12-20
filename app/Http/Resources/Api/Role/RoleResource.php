<?php

namespace App\Http\Resources\Api\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $permissions = json_decode($this->permession, true) ?? [];
        $permissionsAr = config('permessions_ar', []);
        
        // Convert permission keys to Arabic names
        $permissionsWithNames = [];
        foreach ($permissions as $permissionKey) {
            $permissionsWithNames[] = [
                'key' => $permissionKey,
                'name' => $permissionsAr[$permissionKey] ?? $permissionKey,
            ];
        }

        return [
            'id' => $this->id,
            'role' => is_array($this->role) ? ($this->role['ar'] ?? $this->role) : $this->role,
            'permissions' => $permissionsWithNames,
            'users_count' => $this->admins()->count() + $this->users()->count(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

