<?php

namespace App\Http\Controllers\Api\DailyClosing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DailyClosing\CloseDayRequest;
use App\Http\Resources\Api\DailyClosing\DailyClosingResource;
use App\Services\Api\DailyClosing\DailyClosingService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyClosingController extends Controller
{
    protected DailyClosingService $dailyClosingService;

    public function __construct(DailyClosingService $dailyClosingService)
    {
        $this->dailyClosingService = $dailyClosingService;
    }

    /**
     * Get all daily closings
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $result = $this->dailyClosingService->getAllClosings($perPage);

        $data = $result['data'];

        return ApiResponse::paginated(
            DailyClosingResource::collection($data->items()),
            $data,
            'تم جلب الإقفالات اليومية بنجاح'
        );
    }

    /**
     * Get today's data (before closing)
     */
    public function today(): JsonResponse
    {
        $result = $this->dailyClosingService->getTodayData();

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            $result['data'],
            $result['message']
        );
    }

    /**
     * Close current day
     */
    public function store(CloseDayRequest $request): JsonResponse
    {
        $result = $this->dailyClosingService->closeDay($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new DailyClosingResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Get closing by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->dailyClosingService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new DailyClosingResource($result['data']),
            'تم جلب الإقفال بنجاح'
        );
    }

    /**
     * Get closing by specific date
     */
    public function getByDate(string $date): JsonResponse
    {
        $result = $this->dailyClosingService->getByDate($date);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new DailyClosingResource($result['data']),
            'تم جلب إقفال التاريخ المحدد بنجاح'
        );
    }
}
