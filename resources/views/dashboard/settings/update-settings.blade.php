<div>
    <form class="form form-horizontal" wire:submit.prevent='submit'>
        <div class="row">
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.site-name-ar') }}</label>
                <input type="text" class="form-control" wire:model="site_name_ar"
                    placeholder="{{ __('dashboard.site-name-ar') }}">
                @include('dashboard.includes.error', ['property' => 'site_name_ar'])
            </div>
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.site-name') }}</label>
                <input type="text" class="form-control" wire:model="site_name"
                    placeholder="{{ __('dashboard.site-name') }}">
                @include('dashboard.includes.error', ['property' => 'site_name'])
            </div>
        </div>

        <div class="row">
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.site-address-ar') }}</label>
                <input type="text" class="form-control" wire:model="site_address_ar"
                    placeholder="{{ __('dashboard.site-name-ar') }}">
                @include('dashboard.includes.error', ['property' => 'site_address_ar'])
            </div>
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.site-address') }}</label>
                <input type="text" class="form-control" wire:model="site_address"
                    placeholder="{{ __('dashboard.site-name') }}">
                @include('dashboard.includes.error', ['property' => 'site_address'])
            </div>
        </div>

        <div class="row">
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.site-email') }}</label>
                <input type="email" class="form-control" wire:model="site_email"
                    placeholder="{{ __('dashboard.site-name-ar') }}">
                @include('dashboard.includes.error', ['property' => 'site_email'])
            </div>
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.email-support') }}</label>
                <input type="email" class="form-control" wire:model="email_support"
                    placeholder="{{ __('dashboard.site-name') }}">
                @include('dashboard.includes.error', ['property' => 'email_support'])
            </div>
        </div>

        <div class="row">
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.site-phone') }}</label>
                <input type="text" class="form-control" wire:model="site_phone"
                    placeholder="{{ __('dashboard.site-phone') }}">
                @include('dashboard.includes.error', ['property' => 'site_phone'])
            </div>
        </div>

        <div class="mb-2">
            <label class="d-block form-label">{{ __('dashboard.site-desc-ar') }}</label>
            <textarea class="form-control" rows="3" wire:model="site_desc_ar"></textarea>
            @include('dashboard.includes.error', ['property' => 'site_desc_ar'])
        </div>
        <div class="mb-2">
            <label class="d-block form-label">{{ __('dashboard.site-desc') }}</label>
            <textarea class="form-control" rows="3" wire:model="site_desc"></textarea>
            @include('dashboard.includes.error', ['property' => 'site_desc'])
        </div>

        <div class="mb-2">
            <label class="d-block form-label">{{ __('dashboard.about-ar') }}</label>
            <textarea class="form-control" rows="3" wire:model="about_ar"></textarea>
            @include('dashboard.includes.error', ['property' => 'about_ar'])
        </div>
        <div class="mb-2">
            <label class="d-block form-label">{{ __('dashboard.about-en') }}</label>
            <textarea class="form-control" rows="3" wire:model="about_en"></textarea>
            @include('dashboard.includes.error', ['property' => 'about_en'])
        </div>
        {{-- Social media --}}
        <h2 class="text-center mt-2" style="color: blue">{{ __('dashboard.social-media') }}</h2>
        <hr style="color: blue" class="mb-2">

        <div class="row">
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.facebook-url') }}</label>
                <input type="text" class="form-control" wire:model="facebook"
                    placeholder="{{ __('dashboard.facebook-url') }}">
                @include('dashboard.includes.error', ['property' => 'facebook'])

            </div>
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.x-url') }}</label>
                <input type="text" class="form-control" wire:model="x_url"
                    placeholder="{{ __('dashboard.x-url') }}">
                @include('dashboard.includes.error', ['property' => 'x_url'])

            </div>
        </div>

        <div class="row">
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.youtube-url') }}</label>
                <input type="text" class="form-control" wire:model="youtube"
                    placeholder="{{ __('dashboard.youtube-url') }}">
                @include('dashboard.includes.error', ['property' => 'youtube'])
            </div>
        </div>
        {{-- End Social media --}}

        {{-- Media --}}
        <h2 class="text-center mt-2" style="color: blue">{{ __('dashboard.media') }}</h2>
        <hr style="color: blue" class="mb-2">

        <div class="row mb-2">
            <div class="col-6">
                <div class="col-sm-6">
                    <label class="col-form-label">{{ __('dashboard.logo') }}</label>
                </div>
                <div class="form-group">
                    @if (isset($logo) && is_object($logo))
                        <img src="{{ $logo->temporaryUrl() }}" width="150" class="wd-80 ">
                    @else
                        <img src="{{ asset($logo) }}" width="150" class="wd-80 ">
                    @endif
                </div>
                <div class="col-sm-9">
                    <input type="file" class="form-control" wire:model="logo">
                    @include('dashboard.includes.error', ['property' => 'logo'])
                </div>
            </div>
            <div class="col-6">
                <div class="col-sm-6">
                    <label class="col-form-label">{{ __('dashboard.site-favicon') }}</label>
                </div>
                <div class="form-group">
                    @if (isset($favicon) && is_object($favicon))
                        <img src="{{ $favicon->temporaryUrl() }}" width="150" class="wd-80 ">
                    @else
                        <img src="{{ asset($favicon) }}" width="150" class="wd-80 ">
                    @endif
                </div>
                <div class="col-sm-9">
                    <input type="file" class="form-control" wire:model="favicon">
                    @include('dashboard.includes.error', ['property' => 'favicon'])
                </div>
            </div>
        </div>
        {{-- End media --}}

        {{-- Uther --}}
        <h2 class="text-center mt-2" style="color: blue">{{ __('dashboard.uther') }}</h2>
        <hr style="color: blue" class="mb-2">

        <div class="mb-2">
            <label class="d-block form-label">{{ __('dashboard.meta-desc-ar') }}</label>
            <textarea class="form-control" rows="3" wire:model="meta_desc_ar"></textarea>
            @include('dashboard.includes.error', ['property' => 'meta_desc_ar'])
        </div>
        <div class="mb-2">
            <label class="d-block form-label">{{ __('dashboard.meta-desc') }}</label>
            <textarea class="form-control" rows="3" wire:model="meta_desc"></textarea>
            @include('dashboard.includes.error', ['property' => 'meta_desc'])
        </div>

        <div class="row">
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.site-copyright') }}</label>
                <input type="text" class="form-control" wire:model="site_copyright"
                    placeholder="{{ __('dashboard.site-copyright') }}">
                @include('dashboard.includes.error', ['property' => 'site_copyright'])
            </div>
            <div class="mb-1 col-md-6">
                <label class="form-label">{{ __('dashboard.site-promo') }}</label>
                <input type="text" class="form-control" wire:model="promotion_url"
                    placeholder="{{ __('dashboard.site-promo') }}">
                @include('dashboard.includes.error', ['property' => 'promotion_url'])
            </div>
        </div>
        {{-- End uther --}}

        <button type="submit"
            class="btn btn-primary waves-effect waves-float waves-light">{{ __('dashboard.submit') }}</button>
    </form>
</div>
