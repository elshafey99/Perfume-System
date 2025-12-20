<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live="search" class="form-control w-25"
            placeholder="{{ __('dashboard.search-here') }}">
    </div>
    <table class="table">
        <thead>
            <tr>
                {{-- <th>#</th> --}}
                <th>{{ __('dashboard.product') }}</th>
                <th>{{ __('dashboard.show-at-home') }}</th>
                <th>{{ __('dashboard.status') }}</th>
                <th>{{ __('dashboard.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @if ($data->count() > 0)
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox"
                                    {{ $item->show_at_home == 1 ? 'checked' : '' }}
                                    wire:click="updateShowAtHome({{ $item->id }}, {{ $item->show_at_home == 1 ? 0 : 1 }})">
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox"
                                    {{ $item->status == 1 ? 'checked' : '' }}
                                    wire:click="updateStatus({{ $item->id }}, {{ $item->status == 1 ? 0 : 1 }})">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a class="btn btn-danger waves-effect waves-float waves-light" href="#"
                                    data-id="{{ $item->id }}"
                                    wire:click.prevent="$dispatch('flashSellDelete', {id: {{ $item->id }}})"
                                    title="{{ __('dashboard.delete') }}">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                {{ $data->links() }}
            @else
                <td colspan="6">
                    <div class="text-danger text-center">{{ __('dashboard.no-data') }}</div>
                </td>
            @endif
        </tbody>
    </table>
</div>
