<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Http\Requests\Api\Profile\ChangePasswordRequest;
use App\Http\Resources\Api\Auth\UserResource;
use App\Services\Api\Profile\ProfileService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Get user profile
     */
    public function profile(Request $request): JsonResponse
    {
        $result = $this->profileService->getProfile($request->user());

        return ApiResponse::success(
            new UserResource($result['data']),
            __('profile.profile_retrieved_successfully')
        );
    }

    /**
     * Update user profile
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        // Get only the fields that were actually sent in the request
        $data = $request->only(['name', 'email', 'phone', 'position']);

        // Add image if present
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        // Remove null/empty values to only update what was sent
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });

        $result = $this->profileService->updateProfile(
            $request->user(),
            $data
        );

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new UserResource($result['data']),
            $result['message']
        );
    }

    /**
     * Change user password
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->profileService->changePassword(
            $request->user(),
            $validated['current_password'],
            $validated['new_password']
        );

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}
