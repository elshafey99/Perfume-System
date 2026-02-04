<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Setting\UpdateSettingRequest;
use App\Http\Resources\Api\Setting\SettingResource;
use App\Services\Api\Setting\SettingService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Get settings
     */
    public function index(): JsonResponse
    {
        $result = $this->settingService->getSettings();

        return ApiResponse::success(
            new SettingResource($result['data']),
            __('settings.settings_retrieved_successfully')
        );
    }

    /**
     * Update settings
     */
    public function update(UpdateSettingRequest $request): JsonResponse
    {
        // Merge validated data with uploaded files
        $data = $request->validated();
        
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo');
        }
        
        if ($request->hasFile('logo_receipt')) {
            $data['logo_receipt'] = $request->file('logo_receipt');
        }
        
        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon');
        }

        $result = $this->settingService->updateSettings($data);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SettingResource($result['data']),
            $result['message']
        );
    }
}
