<x-updatecomponent title="{{ __('dashboard.update-admin') }}">
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">{{ __('dashboard.image') }}</label>
            
            @if ($old_image && !$image)
                <div class="mb-2">
                    <img src="{{ asset($old_image) }}" alt="Current" 
                        class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            @endif
            
            <input type="file" class="form-control @error('image') is-invalid @enderror"
                wire:model="image" accept="image/*">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            
            @if ($image)
                <div class="mt-2">
                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" 
                        class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('dashboard.name') }} <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                wire:model="name" placeholder="{{ __('dashboard.name') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('dashboard.email') }} <span class="text-danger">*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                wire:model="email" placeholder="{{ __('dashboard.email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('dashboard.password') }} <small class="text-muted">({{ __('dashboard.leave_empty_keep_current') }})</small></label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                wire:model="password" placeholder="{{ __('dashboard.password') }}">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('dashboard.password_confirmation') }}</label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                wire:model="password_confirmation" placeholder="{{ __('dashboard.password_confirmation') }}">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('dashboard.role') }} <span class="text-danger">*</span></label>
            <select class="form-select @error('role_id') is-invalid @enderror" wire:model="role_id">
                <option value="">{{ __('dashboard.choose') }}</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->role }}</option>
                @endforeach
            </select>
            @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('dashboard.status') }}</label>
            <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" wire:model="status" id="statusSwitchUpdate">
                <label class="form-check-label" for="statusSwitchUpdate">
                    {{ $status ? __('dashboard.active') : __('dashboard.inactive') }}
                </label>
            </div>
        </div>
    </div>
</x-updatecomponent>
