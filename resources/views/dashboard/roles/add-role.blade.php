<x-createcomponent title="{{ __('dashboard.create-role') }}" class="btn-success">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('dashboard.role-ar') }}</label>
            <input type="text" class="form-control @error('role.ar') is-invalid @enderror"
                wire:model="role.ar" placeholder="{{ __('dashboard.role-ar') }}">
            @error('role.ar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('dashboard.role-en') }}</label>
            <input type="text" class="form-control @error('role.en') is-invalid @enderror"
                wire:model="role.en" placeholder="{{ __('dashboard.role-en') }}">
            @error('role.en')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">{{ __('dashboard.premession') }}</label>
            <div class="row">
                @if (config('app.locale') == 'ar')
                    @foreach (config('permessions_ar') as $key => $value)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="perm_{{ $key }}"
                                    value="{{ $key }}" wire:model="permession">
                                <label class="form-check-label" for="perm_{{ $key }}">
                                    {{ $value }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach (config('permessions_en') as $key => $value)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="perm_{{ $key }}"
                                    value="{{ $key }}" wire:model="permession">
                                <label class="form-check-label" for="perm_{{ $key }}">
                                    {{ $value }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            @error('permession')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
</x-createcomponent>
