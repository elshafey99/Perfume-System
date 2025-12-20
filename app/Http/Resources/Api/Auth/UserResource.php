<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->image ? asset($this->image) : null,
            'type' => $this->type,
            'position' => $this->position,
            'status' => $this->status,
            'role' => $this->whenLoaded('role', function () {
                return [
                    'id' => $this->role->id,
                    'name' => $this->role->role,
                    'permissions' => json_decode($this->role->permession, true) ?? [],
                ];
            }),
        ];
    }
}

