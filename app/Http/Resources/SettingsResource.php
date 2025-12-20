<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'name' => $this->site_name,
            'desc' => $this->site_desc,
            'phone' => $this->site_phone,
            'address' => $this->site_address,
            'email' => $this->site_email,
            'support' => $this->email_support,
            'facebook' => $this->facebook,
            'xUrl' => $this->xrl,
            'youtube' => $this->youtube,
            'metaDesc' => $this->meta_desc,
            'logo' => $this->logo,
            'favicon' => $this->favicon,
            'copyright' => $this->site_copyright,
            'promotion' => $this->promotion,
            'about' => $this->about_us,
        ];
    }
}
