<x-create-modal title="{{ __('dashboard.create-slider') }}">
    <div class="row">
        <div class="col-md-6">
            <div class="col-sm-6">
                <label class="col-form-label">{{ __('dashboard.live-image') }}</label>
            </div>
            <div class="form-group">
                @if ($banner)
                    <img src="{{ $banner->temporaryUrl() }}" width="150" class="wd-80 ">
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-sm-6">
                <label class="col-form-label">{{ __('dashboard.banner') }}
                    <code>(1300*500)</code>
                </label>
            </div>
            <div class="form-group">
                <input type="file" wire:model='banner' class="form-control">
            </div>
            @include('dashboard.includes.error', ['property' => 'banner'])
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="col-sm-6">
                <label class="col-form-label">{{ __('dashboard.type-ar') }}</label>
            </div>
            <div class="form-group">
                <input type="text" wire:model='type_ar' placeholder="{{ __('dashboard.type-ar') }}"
                    class="form-control">
            </div>
            @include('dashboard.includes.error', ['property' => 'type_ar'])
        </div>
        <div class="col-md-6">
            <div class="col-sm-6">
                <label class="col-form-label">{{ __('dashboard.type-en') }}</label>
            </div>
            <div class="form-group">
                <input type="text" wire:model='type_en' placeholder="{{ __('dashboard.type-en') }}"
                    class="form-control">
            </div>
            @include('dashboard.includes.error', ['property' => 'type_en'])
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="col-sm-6">
                <label class="col-form-label">{{ __('dashboard.title-ar') }}</label>
            </div>
            <div class="form-group">
                <input type="text" wire:model='title_ar' placeholder="{{ __('dashboard.title-ar') }}"
                    class="form-control">
            </div>
            @include('dashboard.includes.error', ['property' => 'title_ar'])
        </div>
        <div class="col-md-6">
            <div class="col-sm-6">
                <label class="col-form-label">{{ __('dashboard.title-en') }}</label>
            </div>
            <div class="form-group">
                <input type="text" wire:model='title_en' placeholder="{{ __('dashboard.title-en') }}"
                    class="form-control">
            </div>
            @include('dashboard.includes.error', ['property' => 'title_en'])
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="col-sm-6">
                <label class="col-form-label">{{ __('dashboard.start-price') }}</label>
            </div>
            <div class="form-group">
                <input type="number" wire:model='starting_price' placeholder="{{ __('dashboard.start-price') }}"
                    class="form-control">
            </div>
            @include('dashboard.includes.error', ['property' => 'starting_price'])
        </div>
        <div class="col-md-6">
            <div class="col-sm-6">
                <label class="col-form-label">{{ __('dashboard.serial') }}</label>
            </div>
            <div class="form-group">
                <input type="number" wire:model='serial' placeholder="{{ __('dashboard.serial') }}"
                    class="form-control">
            </div>
            @include('dashboard.includes.error', ['property' => 'serial'])
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="col-sm-6">
                <label class="col-form-label">{{ __('dashboard.btn-url') }}</label>
            </div>
            <div class="form-group">
                <input type="url" wire:model='btn_url' placeholder="{{ __('dashboard.btn-url') }}"
                    class="form-control">
            </div>
            @include('dashboard.includes.error', ['property' => 'btn_url'])
        </div>
        <div class="col-md-6">
            <div class="col-sm-6">
                <label class="col-form-label">{{ __('dashboard.status') }}</label>
            </div>
            <div class="form-group">
                <select wire:model="status" wire:loading.attr="disabled" class="form-control" wire:target="status">
                    <option selected>{{ __('dashboard.select-status') }}</option>
                    <option value="1">{{__('dashboard.active')}}</option>
                    <option value="0">{{__('dashboard.inactive')}}</option>
                </select>
            </div>
            @include('dashboard.includes.error', ['property' => 'status'])
        </div>
    </div>
</x-create-modal>
