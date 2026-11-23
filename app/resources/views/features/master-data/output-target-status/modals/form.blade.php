<div wire:ignore.self class="modal fade" id="modal_target_status" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #2b2f3a;">

            <!-- HEADER -->
            <div class="modal-header text-white border-0" style="background-color: #0070C0;">
                <h6 class="modal-title">
                    {{ $isEdit ? 'Ubah Status' : 'Tambah Status Baru' }}
                </h6>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        wire:click="resetFields"></button>
            </div>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body text-white">

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Nama Status Capaian
                        </label>

                        <input type="text"
                               wire:model="name"
                               class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror"
                               placeholder="Contoh: Planned, Submitted, Published">

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text text-light opacity-75">
                            Status ini akan digunakan dosen saat melaporkan progres luaran penelitian.
                        </div>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn btn-link text-secondary"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn text-white"
                            style="background-color: #0070C0; border-color: #0070C0;">
                        {{ $isEdit ? 'Update' : 'Simpan Data' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>



