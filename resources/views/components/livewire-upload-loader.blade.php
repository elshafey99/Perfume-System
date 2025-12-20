{{--  Spinner أثناء رفع الصورة --}}
<div wire:loading wire:target="image" class="mt-2">
    <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
    {{-- <span>{{ __('dashboard.uploading_in_progress') }}</span> --}}
</div>
