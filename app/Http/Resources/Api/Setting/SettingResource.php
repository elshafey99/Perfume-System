<?php

namespace App\Http\Resources\Api\Setting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'site_name' => $this->site_name,
            'site_phone' => $this->site_phone,
            'site_address' => $this->site_address,
            'logo' => $this->logo ? asset($this->logo) : null,
            'favicon' => $this->favicon ? asset($this->favicon) : null,
            'site_copyright' => $this->site_copyright,
            
            // 'site_desc' => $this->site_desc,
            // 'site_email' => $this->site_email,
            // 'email_support' => $this->email_support,
            // 'facebook' => $this->facebook,
            // 'x_url' => $this->x_url,
            // 'youtube' => $this->youtube,
            // 'meta_desc' => $this->meta_desc,
            // 'promotion_url' => $this->promotion_url,
            // 'about_us' => $this->about_us,
            
            // POS Settings
            'default_tax_rate' => (float) ($this->default_tax_rate ?? 0),
            'default_discount_rate' => (float) ($this->default_discount_rate ?? 0),
            'receipt_thank_you_message' => $this->receipt_thank_you_message,
            
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
