@extends('dashboard.master', ['title' => 'Flash Sell'])
@section('flash-sell-active', 'active')
@section('settings-open', 'open')
@section('content')
    <h4 class="card-title">{{ __('dashboard.flash-sell') }}</h4>
    <div class="col-lg-12 col-md-12 col-12 col-sm-12">
        <div class="card">
            <div class="card-header ">
                <h4>{{ __('dashboard.end-flash-sell') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{route('dashboard.flash-sell.sitDate')}}" method="POST">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('dashboard.end-date') }}</label><br>
                            <input type="date" name="end_date" class="form-control" value="{{$flash_sell->end_date}}">
                        </div>
                        @include('dashboard.includes.error', [
                            'property' => 'end_date',
                        ])
                    </div>
                    <button type="submit" class="btn btn-primary float-right my-3">{{ __('dashboard.submit') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('dashboard.flash-sell-item') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('dashboard.flash-sell.sitProductToSell')}}" method="POST">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ __('dashboard.product') }}</label>
                                    <select class="form-control select2" name="product_id">
                                        <option value="">{{ __('dashboard.select-product') }}</option>
                                        @foreach ($products as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @include('dashboard.includes.error', ['property' => 'product_id'])
                                </div>
                            </div>
                        </div>
                        {{-- status , show at home  --}}
                        <div class="row mb-2">
                            <div class="mb-1 col-6">
                                <div class="col-sm-6">
                                    <label class="form-label">{{ __('dashboard.status') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select" name="status">
                                        <option value="">{{ __('dashboard.select-status') }}</option>
                                        <option value="1">{{ __('dashboard.active') }}</option>
                                        <option value="0">{{ __('dashboard.inactive') }}</option>
                                    </select>
                                </div>
                                @include('dashboard.includes.error', ['property' => 'status'])
                            </div>
                            <div class="mb-1 col-6">
                                <div class="col-sm-6">
                                    <label class="form-label">{{ __('dashboard.show-at-home') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select" name="show_at_home">
                                        <option value="">{{ __('dashboard.select-show-at-home') }}</option>
                                        <option value="1">{{ __('dashboard.yes') }}</option>
                                        <option value="0">{{ __('dashboard.no') }}</option>
                                    </select>
                                </div>
                                @include('dashboard.includes.error', ['property' => 'show_at_home'])
                            </div>
                        </div>
                        <div class=" mt-2 col-sm-9">
                            <button type="submit"
                                class="btn btn-primary me-1 waves-effect waves-float waves-light">{{ __('dashboard.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('dashboard.flash-sell-items') }}</h4>
                </div>
                <div class="card-body">
                    @livewire('dashboard.settings.flash-sell.flash-sell-data')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
@endpush
@push('js')
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dropdownAutoWidth: true,
                dropdownParent: $('.select2').parent()
            });
        });
    </script>

        {{-- Scripts from livewire success msg --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('flashSellShowAtHomeUpdate', function() {
                    Swal.fire({
                        position: 'top-start',
                        icon: 'success',
                        title: '{{ __('dashboard.show-at-home-change') }}',
                        showConfirmButton: false,
                        timer: 1500,
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                });
            });
            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('flashSellStatusUpdate', function() {
                    Swal.fire({
                        position: 'top-start',
                        icon: 'success',
                        title: '{{ __('dashboard.status-change') }}',
                        showConfirmButton: false,
                        timer: 1500,
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                });
            });
        </script>
        {{-- End scripts from livewire success msg --}}
        {{-- Scripts from seewtalert delete livewire --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('flashSellDelete', function(data) {
                    Swal.fire({
                        title: "{{ __('dashboard.are_you_sure') }}",
                        text: "{{ __('dashboard.confirm_delete_message') }}",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "{{ __('dashboard.yes_delete') }}",
                        cancelButtonText: "{{ __('dashboard.cancel') }}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('deleteItem', {
                                id: data.id
                            });
                        }
                    });
                });

                window.addEventListener('itemDeleted', function() {
                    Swal.fire({
                        title: "{{ __('dashboard.success') }}",
                        text: "{{ __('dashboard.item_deleted_successfully') }}",
                        icon: "success",
                        timer: 3000
                    });
                });
            });
        </script>
        {{-- End scripts from seewtalert delete livewire --}}
@endpush

