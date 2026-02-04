<?php

namespace App\Services\Api\Setting;

use App\Repositories\Api\Setting\SettingRepository;
use App\Helpers\FileHelper;

class SettingService
{
    protected SettingRepository $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Get settings
     */
    public function getSettings(): array
    {
        $settings = $this->settingRepository->get();

        // Create default settings if not exists
        if (!$settings) {
            $settings = $this->settingRepository->createIfNotExists();
        }

        return [
            'success' => true,
            'data' => $settings,
        ];
    }

    /**
     * Update settings
     */
    public function updateSettings(array $data): array
    {
        $settings = $this->settingRepository->get();

        if (!$settings) {
            $settings = $this->settingRepository->createIfNotExists();
        }

        try {
            // Handle logo upload using FileHelper
            if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
                $logoPath = FileHelper::uploadImage(
                    $data['logo'],
                    'uploads/images',
                    $settings->logo // Old file path to delete
                );
                $data['logo'] = $logoPath;
            } else {
                // Remove from update if not uploaded
                unset($data['logo']);
            }

            // Handle logo_receipt upload using FileHelper
            if (isset($data['logo_receipt']) && $data['logo_receipt'] instanceof \Illuminate\Http\UploadedFile) {
                $logoReceiptPath = FileHelper::uploadImage(
                    $data['logo_receipt'],
                    'uploads/images',
                    $settings->logo_receipt // Old file path to delete
                );
                $data['logo_receipt'] = $logoReceiptPath;
            } else {
                // Remove from update if not uploaded
                unset($data['logo_receipt']);
            }

            // Handle favicon upload using FileHelper
            if (isset($data['favicon']) && $data['favicon'] instanceof \Illuminate\Http\UploadedFile) {
                $faviconPath = FileHelper::uploadImage(
                    $data['favicon'],
                    'uploads/images',
                    $settings->favicon // Old file path to delete
                );
                $data['favicon'] = $faviconPath;
            } else {
                // Remove from update if not uploaded
                unset($data['favicon']);
            }

            $this->settingRepository->update($settings, $data);

            return [
                'success' => true,
                'data' => $settings->fresh(),
                'message' => __('settings.settings_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('settings.settings_update_failed') . ': ' . $e->getMessage(),
            ];
        }
    }
}
