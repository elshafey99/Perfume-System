<?php

namespace App\Http\Resources\PropertyMember;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->user;
        $role = $this->role;
        $property = $this->property;

        $lang = $request->header('Accept-Language', 'en');

        return [
            'id' => $this->id,
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'birth_date' => optional($user->birth_date)?->toDateString(),
                'image' => $user && $user->image ? asset($user->image) : null,
                'member_id' => $user->member_id,
                'access_code' => $user->access_code,
            ] : null,
            'role' => $role ? [
                'id' => $role->id,
                'name_key' => $role->name_key,
                'display_name' => $role->getTranslation('display_name',$lang),
            ] : null,
            'property' => $property ? [
                'code' => $property->code,
            ] : null,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
