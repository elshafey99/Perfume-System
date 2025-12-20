<div>
    <div class="card-body pb-0">
        <div class="row">
            <div class="col-md-4 col-12 mb-2">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i data-feather="search"></i></span>
                    <input wire:model.live="search" type="text" class="form-control"
                        placeholder="{{ __('dashboard.search') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th class="text-center align-middle" style="width: 5%;">#</th>
                    <th class="align-middle" style="width: 10%;">{{ __('dashboard.image') }}</th>
                    <th class="align-middle" style="width: 20%;">{{ __('dashboard.name') }}</th>
                    <th class="align-middle" style="width: 20%;">{{ __('dashboard.email') }}</th>
                    <th class="align-middle" style="width: 15%;">{{ __('dashboard.role') }}</th>
                    <th class="text-center align-middle" style="width: 10%;">{{ __('dashboard.status') }}</th>
                    <th class="text-center align-middle" style="width: 20%;">{{ __('dashboard.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @if ($data->count() > 0)
                    @foreach ($data as $item)
                        <tr wire:key="admin-{{ $item->id }}" style="height: 80px;">
                            <td class="text-center align-middle">
                                <span
                                    class="fw-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                            </td>
                            <td class="align-middle">
                                @if ($item->image)
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="rounded"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="avatar bg-light-primary rounded" style="width: 50px; height: 50px;">
                                        <div class="avatar-content">
                                            <i data-feather="user" class="font-medium-3"></i>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle">
                                <strong class="text-primary">{{ $item->name }}</strong>
                            </td>
                            <td class="align-middle">
                                <span class="text-muted">{{ $item->email }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge rounded-pill bg-info">{{ $item->role->role ?? '-' }}</span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox"
                                        wire:click="toggleStatus({{ $item->id }})"
                                        {{ $item->status ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    <button type="button"
                                        onclick="Livewire.dispatch('editItem', { id: {{ $item->id }} })"
                                        title="{{ __('dashboard.update') }}"
                                        class="btn btn-icon rounded-circle btn-sm btn-warning action-btn">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button"
                                        onclick="Livewire.dispatch('deleteConfirm', { id: {{ $item->id }} })"
                                        title="{{ __('dashboard.delete') }}"
                                        class="btn btn-icon rounded-circle btn-sm btn-danger action-btn">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr style="height: 80px;">
                        <td colspan="7" class="text-center align-middle">
                            <div class="text-muted">
                                <i data-feather="inbox" class="mb-1"></i>
                                <p class="mb-0">{{ __('dashboard.no-data') }}</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>
