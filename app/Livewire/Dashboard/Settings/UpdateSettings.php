<?php

namespace App\Livewire\Dashboard\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Livewire\TemporaryUploadedFile;

class UpdateSettings extends Component
{
    use WithFileUploads;
    protected $listeners = ['refresh'];
    public $settings, $site_name_ar, $site_name, $site_desc_ar, $site_desc, $site_phone,
        $site_address_ar, $site_address, $promotion_url, $site_email, $email_support,
        $facebook, $x_url, $youtube, $meta_desc, $meta_desc_ar, $logo, $favicon, $site_copyright ,
        $about_ar, $about_en ;
    public function mount()
    {
        // fill the $settings with the eloquent modal of the same id
        $this->settings            = Setting::first();
        $this->site_name_ar        = $this->settings->getTranslation('site_name', 'ar');
        $this->site_name           = $this->settings->getTranslation('site_name', 'en');
        $this->site_desc_ar        = $this->settings->getTranslation('site_desc', 'ar');
        $this->site_desc           = $this->settings->getTranslation('site_desc', 'en');
        $this->site_phone          = $this->settings->site_phone;
        $this->site_address_ar     = $this->settings->getTranslation('site_address', 'ar');
        $this->site_address        = $this->settings->getTranslation('site_address', 'en');
        $this->about_ar            = $this->settings->getTranslation('about_us', 'ar');
        $this->about_en            = $this->settings->getTranslation('about_us', 'en');
        $this->site_email          = $this->settings->site_email;
        $this->email_support       = $this->settings->email_support;
        $this->facebook            = $this->settings->facebook;
        $this->x_url               = $this->settings->x_url;
        $this->youtube             = $this->settings->youtube;
        $this->meta_desc_ar        = $this->settings->getTranslation('meta_desc', 'ar');
        $this->meta_desc           = $this->settings->getTranslation('meta_desc', 'en');
        $this->logo                = $this->settings->logo;
        $this->favicon             = $this->settings->favicon;
        $this->site_copyright      = $this->settings->site_copyright;
        $this->promotion_url       = $this->settings->promotion_url;
        $this->resetValidation();
        // show the edit modal
    }

    public function rules()
    {
        $rules = [
            'site_name_ar'   => ['required', 'string'],
            'site_name'      => ['required', 'string'],
            'site_desc_ar'   => ['required', 'string'],
            'site_desc'      => ['required', 'string'],
            'about_ar'       => ['required', 'string'],
            'about_en'       => ['required', 'string'],
            'site_phone'     => ['required'],
            'site_address_ar'=> ['required', 'string'],
            'site_address'   => ['required', 'string'],
            'site_email'     => ['required', 'email'],
            'email_support'  => ['required', 'email'],
            'facebook'       => ['nullable', 'url'],
            'x_url'          => ['nullable', 'url'],
            'youtube'        => ['nullable', 'url'],
            'meta_desc_ar'   => ['required', 'string'],
            'meta_desc'      => ['required', 'string'],
            'site_copyright' => ['required'],
            'promotion_url'  => ['required', 'url'],
        ];
        if ($this->logo && $this->logo instanceof TemporaryUploadedFile) {
            $rules['logo'] = ['image', 'mimes:jpg,jpeg,png,gif'];
        } else {
            $rules['logo'] = ['nullable'];
        }
        if ($this->favicon && $this->favicon instanceof TemporaryUploadedFile) {
            $rules['favicon'] = ['image', 'mimes:jpg,jpeg,png,gif'];
        } else {
            $rules['favicon'] = ['nullable'];
        }

        return $rules;
    }




    public function submit()
    {
        $data = $this->validate();
        if ($this->logo instanceof UploadedFile && $this->logo->isValid()) {
            // === Delete The Old Image If It Exists
            if ($this->settings->logo && file_exists(public_path($this->settings->logo))) {
                unlink(public_path($this->settings->logo));
            }
            // === Save New Image In Folder Uploads
            $logoName = uniqid() . '_' . $this->logo->getClientOriginalName();
            $this->logo->storePubliclyAs('uploads/settings', $logoName, 'public');
            $this->settings->logo = 'uploads/settings/' . $logoName;
        }
        if ($this->favicon instanceof UploadedFile && $this->favicon->isValid()) {
            // === Delete The Old Image If It Exists
            if ($this->settings->favicon && file_exists(public_path($this->settings->favicon))) {
                unlink(public_path($this->settings->favicon));
            }
            // === Save New Image In Folder Uploads
            $faviconName = uniqid() . '_' . $this->favicon->getClientOriginalName();
            $this->favicon->storePubliclyAs('uploads/settings', $faviconName, 'public');
            $this->settings->favicon = 'uploads/settings/' . $faviconName;
        }
        // === Update Data In DB
        $this->settings->update([
            'site_name'      => [
                'ar' => $data['site_name_ar'],
                'en' => $data['site_name'],
            ],
            'site_desc'      => [
                'ar' => $data['site_desc_ar'],
                'en' => $data['site_desc'],
            ],
            'site_phone'     => $data['site_phone'],
            'site_address'   => [
                'ar' => $data['site_address_ar'],
                'en' => $data['site_address'],
            ],
            'about_us'       => [
                'ar' => $data['about_ar'],
                'en' => $data['about_en'],
            ],
            'site_email'     => $data['site_email'],
            'email_support'  => $data['email_support'],
            'facebook'       => $data['facebook'],
            'x_url'          => $data['x_url'],
            'youtube'        => $data['youtube'],
            'meta_desc'      => [
                'ar' => $data['meta_desc_ar'],
                'en' => $data['meta_desc'],
            ],
            'site_copyright' => $data['site_copyright'],
            'promotion_url'  => $data['promotion_url'],

        ]);
        // === Send Creat MS To Component
        $this->dispatch('settingUpdateMS');
        // === Refresh Coupon Data Component
        $this->dispatch('refresh')->to(UpdateSettings::class);
    }
    public function render()
    {
        return view('dashboard.settings.update-settings');
    }
}
