<?php

namespace App\Http\Controllers\Api\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Role\StoreRoleRequest;
use App\Http\Requests\Api\Role\UpdateRoleRequest;
use App\Http\Resources\Api\Role\RoleResource;
use App\Services\Api\Role\RoleService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Get all roles
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        
        $result = $this->roleService->getAll($perPage);

        if (!$result['success']) {
            return ApiResponse::error($result['message'] ?? 'Error', 400);
        }

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                RoleResource::collection($data->items()),
                $data,
                __('roles.roles_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            RoleResource::collection($data),
            __('roles.roles_retrieved_successfully')
        );
    }

    /**
     * Get role by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->roleService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new RoleResource($result['data']),
            __('roles.role_retrieved_successfully')
        );
    }

    /**
     * Get available permissions
     */
    public function permissions(): JsonResponse
    {
        $result = $this->roleService->getPermissions();

        return ApiResponse::success(
            $result['data'],
            __('roles.permissions_retrieved_successfully')
        );
    }

    /**
     * Create new role
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $result = $this->roleService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new RoleResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update role
     */
    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        $result = $this->roleService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new RoleResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete role
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->roleService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}

