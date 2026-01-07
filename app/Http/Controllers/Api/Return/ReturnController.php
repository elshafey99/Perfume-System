<?php

namespace App\Http\Controllers\Api\Return;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Return\StoreReturnRequest;
use App\Http\Requests\Api\Return\ProcessReturnRequest;
use App\Http\Resources\Api\Return\ReturnResource;
use App\Services\Api\Return\ReturnService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    protected ReturnService $returnService;

    public function __construct(ReturnService $returnService)
    {
        $this->returnService = $returnService;
    }

    /**
     * Get all returns
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $result = $this->returnService->getAll($perPage, $status, $dateFrom, $dateTo);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                ReturnResource::collection($data->items()),
                $data,
                __('returns.returns_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            ReturnResource::collection($data),
            __('returns.returns_retrieved_successfully')
        );
    }

    /**
     * Get return by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->returnService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new ReturnResource($result['data']),
            __('returns.return_retrieved_successfully')
        );
    }

    /**
     * Create new return request
     */
    public function store(StoreReturnRequest $request): JsonResponse
    {
        $result = $this->returnService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new ReturnResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Approve return
     */
    public function approve(int $id): JsonResponse
    {
        $result = $this->returnService->approve($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new ReturnResource($result['data']),
            $result['message']
        );
    }

    /**
     * Reject return
     */
    public function reject(ProcessReturnRequest $request, int $id): JsonResponse
    {
        $notes = $request->input('notes');
        $result = $this->returnService->reject($id, $notes);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new ReturnResource($result['data']),
            $result['message']
        );
    }

    /**
     * Process return (complete the refund/exchange/store credit)
     */
    public function process(int $id): JsonResponse
    {
        $result = $this->returnService->process($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new ReturnResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete return (only pending)
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->returnService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }

    /**
     * Get return statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $result = $this->returnService->getStatistics($dateFrom, $dateTo);

        return ApiResponse::success(
            $result['data'],
            __('returns.statistics_retrieved_successfully')
        );
    }
}
