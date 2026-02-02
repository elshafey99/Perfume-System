<?php

namespace App\Repositories\Api\Setting;

use App\Models\Setting;

class SettingRepository
{
    /**
     * Get settings (there's only one record)
     */
    public function get(): ?Setting
    {
        return Setting::first();
    }

    /**
     * Update settings
     */
    public function update(Setting $setting, array $data): bool
    {
        return $setting->update($data);
    }

    /**
     * Create initial settings if not exists
     */
    public function createIfNotExists(): Setting
    {
        $existing = $this->get();
        
        if ($existing) {
            return $existing;
        }

        return Setting::create([
            'site_name' => ['ar' => 'نظام العطور', 'en' => 'Perfume System'],
            //'site_desc' => ['ar' => 'نظام إدارة متكامل', 'en' => 'Complete Management System'],
            'default_tax_rate' => 0,
            'default_discount_rate' => 0,
        ]);
    }
}
