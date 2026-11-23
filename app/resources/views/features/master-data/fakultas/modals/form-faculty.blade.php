<div wire:ignore.self class="modal fade" id="modal_fakultas" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">

            <!-- HEADER -->
            <div class="modal-header text-white border-0" style="background-color: #0070C0;">
                <h6 class="modal-title fw-bold d-flex align-items-center">
                    <i class="{{ $isEdit ? 'ph-bold ph-pencil-line' : 'ph-bold ph-plus-circle' }} me-2"></i>
                    {{ $isEdit ? 'Edit Fakultas' : 'Tambah Fakultas' }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body p-4">

                    <!-- INPUT -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-body">Nama Fakultas</label>

                        <input type="text"
                               wire:model="name"
                               class="form-control border-light shadow-sm @error('name') is-invalid @enderror"
                               placeholder="Contoh: Fakultas Informatika & Teknik Elektro"
                               style="border-radius: 8px;">

                        @error('name')
                            <div class="invalid-feedback d-block">
                                <i class="ph-bold ph-warning-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- INFO EDIT -->
                    @if ($isEdit)
                        <div class="alert border-0 mb-0 d-flex align-items-center shadow-sm"
                             style="background-color: #e8f5e9; color: #2e7d32; border-radius: 8px;">
                            <i class="ph-bold ph-info me-2 fs-4"></i>
                            <small>
                                ID: <strong>{{ $selected_id }}</strong> -
                                Perubahan nama akan otomatis memperbarui data terkait di tingkat Prodi.
                            </small>
                        </div>
                    @endif

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-link text-muted fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn shadow-sm px-4 fw-bold text-white"
                            style="background-color: #0070C0; border-color: #0070C0; border-radius: 8px;">
                        <i class="ph-bold ph-floppy-disk me-2"></i>
                        {{ $isEdit ? 'Update' : 'Simpan Data' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
.form-control:focus {
    border-color: #0070C0 !important;
    box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.15) !important;
}
</style>



