<?php

namespace App\Http\Controllers\Api\UnitType;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UnitType\StoreUnitTypeRequest;
use App\Http\Requests\Api\UnitType\UpdateUnitTypeRequest;
use App\Http\Resources\Api\UnitType\UnitTypeResource;
use App\Services\Api\UnitType\UnitTypeService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    protected UnitTypeService $unitTypeService;

    public function __construct(UnitTypeService $unitTypeService)
    {
        $this->unitTypeService = $unitTypeService;
    }

    /**
     * Get all unit types
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $activeOnly = $request->has('active_only') ? $request->boolean('active_only') : null;

        $result = $this->unitTypeService->getAll($perPage, $activeOnly);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                UnitTypeResource::collection($data->items()),
                $data,
                __('unit_types.unit_types_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            UnitTypeResource::collection($data),
            __('unit_types.unit_types_retrieved_successfully')
        );
    }

    /**
     * Get unit type by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->unitTypeService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new UnitTypeResource($result['data']),
            __('unit_types.unit_type_retrieved_successfully')
        );
    }

    /**
     * Create new unit type
     */
    public function store(StoreUnitTypeRequest $request): JsonResponse
    {
        $result = $this->unitTypeService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new UnitTypeResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update unit type
     */
    public function update(UpdateUnitTypeRequest $request, int $id): JsonResponse
    {
        $result = $this->unitTypeService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new UnitTypeResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete unit type
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->unitTypeService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}

