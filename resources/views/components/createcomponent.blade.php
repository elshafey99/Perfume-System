<div class="modal fade text-start modal-success" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog {{ $size ?? 'modal-lg' }} modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">{{ $title }}</h5>
                <button type="button" class="btn btn-sm btn-danger rounded-circle" data-bs-dismiss="modal"
                    aria-label="Close"
                    style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: bold; line-height: 1;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form class="form form-horizontal" wire:submit.prevent="submit">
                <div class="modal-body">
                    {{ $slot }}
                </div>

                <div class="modal-footer d-flex justify-content-end gap-2">

                    {{-- Cancel Button --}}
                    <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        {{ __('dashboard.cancel') }}
                    </button>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="btn {{ $class ?? 'btn-success' }} text-white waves-effect waves-float waves-light"
                        wire:loading.attr="disabled" wire:target="submit,image,file,new_logo,new_favicon,upload"
                        @disabled(isset($uploading) && $uploading) style="transition: opacity 0.2s ease;">
                        <i class="fas fa-check me-1"></i>
                        {{ __('dashboard.submit') }}
                    </button>


                </div>

                <script>
                    document.addEventListener('livewire:load', () => {
                        Livewire.on('resetFileUpload', () => {
                            document.querySelectorAll('input[type="file"]').forEach(input => input.value = '');
                        });
                    });
                </script>

            </form>
        </div>
    </div>
</div>
