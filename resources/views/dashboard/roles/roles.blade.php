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
                    <th class="align-middle" style="width: 15%;">{{ __('dashboard.role') }}</th>
                    <th class="align-middle" style="width: 65%;">{{ __('dashboard.premession') }}</th>
                    <th class="text-center align-middle" style="width: 15%;">{{ __('dashboard.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @if ($data->count() > 0)
                    @foreach ($data as $item)
                        <tr wire:key="role-{{ $item->id }}" style="height: 80px;">
                            <td class="text-center align-middle">
                                <span
                                    class="fw-bold">{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</span>
                            </td>
                            <td class="align-middle">
                                <strong class="text-primary">{{ $item->role }}</strong>
                            </td>
                            <td class="align-middle">
                                <div style="max-height: 60px; overflow-y: auto; overflow-x: hidden;">
                                    @if (config('app.locale') == 'ar')
                                        @foreach (json_decode($item->permession) as $perm)
                                            @if (isset(config('permessions_ar')[$perm]))
                                                <span class="badge rounded-pill bg-primary me-1 mb-1"
                                                    style="font-size: 0.75rem;">
                                                    {{ config('permessions_ar')[$perm] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach (json_decode($item->permession) as $perm)
                                            @if (isset(config('permessions_en')[$perm]))
                                                <span class="badge rounded-pill bg-primary me-1 mb-1"
                                                    style="font-size: 0.75rem;">
                                                    {{ config('permessions_en')[$perm] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    @endif
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
                        <td colspan="4" class="text-center align-middle">
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
