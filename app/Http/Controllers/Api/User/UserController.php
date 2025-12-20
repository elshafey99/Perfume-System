<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreUserRequest;
use App\Http\Requests\Api\User\UpdateUserRequest;
use App\Http\Requests\Api\User\ChangeStatusRequest;
use App\Http\Resources\Api\Auth\UserResource;
use App\Services\Api\User\UserService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get all users
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $type = $request->input('type'); // 'admin' or 'employee'
        $status = $request->has('status') ? $request->boolean('status') : null;

        $result = $this->userService->getAll($perPage, $type, $status);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                UserResource::collection($data->items()),
                $data,
                __('users.users_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            UserResource::collection($data),
            __('users.users_retrieved_successfully')
        );
    }

    /**
     * Get user by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->userService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new UserResource($result['data']),
            __('users.user_retrieved_successfully')
        );
    }

    /**
     * Create new user
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $result = $this->userService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new UserResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update user
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $result = $this->userService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new UserResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete user
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->userService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }

    /**
     * Change user status
     */
    public function changeStatus(ChangeStatusRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $status = $validated['status'];
        $result = $this->userService->changeStatus($id, $status);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new UserResource($result['data']),
            $result['message']
        );
    }
}

