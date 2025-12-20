<x-createcomponent title="{{ __('dashboard.create-admin') }}" class="btn-success">
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">{{ __('dashboard.image') }}</label>
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
            <label class="form-label">{{ __('dashboard.password') }} <span class="text-danger">*</span></label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                wire:model="password" placeholder="{{ __('dashboard.password') }}">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('dashboard.password_confirmation') }} <span class="text-danger">*</span></label>
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
                <input class="form-check-input" type="checkbox" wire:model="status" id="statusSwitch">
                <label class="form-check-label" for="statusSwitch">
                    {{ $status ? __('dashboard.active') : __('dashboard.inactive') }}
                </label>
            </div>
        </div>
    </div>
</x-createcomponent>
